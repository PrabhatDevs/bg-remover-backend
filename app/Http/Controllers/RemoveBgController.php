<?php

namespace App\Http\Controllers;

use App\Models\DownloadCount;
use App\Models\ProcessedImages;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RemoveBgController extends Controller
{
    public function removeBackground(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        // 1. Upload Original to R2
        // Changed disk to 'r2' to keep it consistent
        $path = $request->file('image')->store('originals', 'r2');
        $originalUrl = Storage::disk('r2')->url($path);

        // 2. Create DB record for BOTH Users and Guests
        // This ensures we always have a record to update later
        $user = $request->user();
        $dbRecord = ProcessedImages::create([
            'user_id' => $user ? $user->id : null,
            'ip_address' => $request->ip(),
            'original_url' => $originalUrl,
            'status' => 'pending',
            'result_url' => '', // Place-holder since it's NOT NULL in DB
        ]);

        // 3. Call Replicate
        $response = Http::withToken(config('services.replicate.token'))
            ->withHeaders(['Prefer' => 'wait'])
            ->post('https://api.replicate.com/v1/predictions', [
                'version' => 'a029dff38972b5fda4ec5d75d7d1cd25aeff621d2cf4946a41055d7db66b80bc',
                'input' => [
                    'image' => $originalUrl,
                    'format' => 'png',
                    'reverse' => false,
                    'threshold' => 0,
                    'background_type' => 'rgba'
                ],
            ]);

        if ($response->failed()) {
            $dbRecord->update(['status' => 'failed']);
            return response()->json([
                'message' => 'AI Service Error',
                'error' => $response->json(),
            ], 500);
        }

        $prediction = $response->json();
        $processedUrl = $prediction['output'] ?? null;

        // 4. Polling Fallback (Fixed config path here)
        if (!$processedUrl && $prediction['status'] !== 'succeeded') {
            for ($i = 0; $i < 10; $i++) {
                sleep(2);
                $check = Http::withToken(config('services.replicate.token'))
                    ->get("https://api.replicate.com/v1/predictions/{$prediction['id']}");

                if ($check->json('status') === 'succeeded') {
                    $processedUrl = $check->json('output');
                    break;
                }
                if ($check->json('status') === 'failed')
                    break;
            }
        }

        if (!$processedUrl) {
            $dbRecord->update(['status' => 'failed']);
            return response()->json(['message' => 'Processing timeout'], 504);
        }

        // 5. Save Processed Result to R2
        $imageContent = file_get_contents($processedUrl);
        $processedFileName = 'processed/' . Str::random(40) . '.png';
        Storage::disk('r2')->put($processedFileName, $imageContent);
        $finalResultUrl = Storage::disk('r2')->url($processedFileName);

        // 6. Final DB Update (Now handles the NOT NULL result_url)
        $dbRecord->update([
            'result_url' => $finalResultUrl,
            'status' => 'completed',
        ]);


        return response()->json([
            'message' => 'Background removed successfully!',
            'original_image_url' => $originalUrl,
            'processed_image_url' => $finalResultUrl,
        ], 200);
    }
    public function downloadImage($filename)
    {
        $path = 'processed/' . $filename;

        if (!Storage::disk('r2')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('r2')->get($path);

        return response($file, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
    public function getRemoveBgCount()
    {
        // The efficient way
        $count = ProcessedImages::count();
        return response()->json(['count' => $count], 200);
    }
    public function myGallery(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $images = ProcessedImages::where('user_id', $user->id)
            ->select('id', 'original_url', 'result_url', 'status')
            ->latest()
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'original_url' => $image->original_url,
                    'result_url' => $image->result_url,
                    'status' => $image->status,
                    'date_human' => $image->created_at->diffForHumans(), // "2 hours ago"
                ];
            });

        return response()->json([
            'success' => true,
            'images' => $images,
        ], 200);
    }
}
