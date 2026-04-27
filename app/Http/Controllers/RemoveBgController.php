<?php

namespace App\Http\Controllers;

use App\Jobs\RemoveBackgroundJob;
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
            'image' => 'required|file|image|max:10240',
        ]);

        // ❌ Block if multiple files sent using images[]
        if ($request->hasFile('images')) {
            return response()->json([
                'message' => 'Only one image allowed. Please upload a single file.'
            ], 422);
        }

        // ❌ Extra safety: if image is array (rare but possible)
        if (is_array($request->file('image'))) {
            return response()->json([
                'message' => 'Multiple files detected. Only one image allowed.'
            ], 422);
        }

        if (!$request->hasFile('image')) {
            return response()->json([
                'message' => 'No image uploaded'
            ], 400);
        }
        $file = $request->file('image');
        $path = $file->store('originals', 'r2');
        $originalUrl = Storage::disk('r2')->url($path);
        $user = $request->user();
        $dbRecord = ProcessedImages::create([
            'user_id' => $user ? $user->id : null,
            'ip_address' => $request->ip(),
            'original_url' => $originalUrl,
            'status' => 'processing',
            'result_url' => '',
        ]);

        RemoveBackgroundJob::dispatch($dbRecord->id, $originalUrl);

        return response()->json([
            'job_id' => $dbRecord->id,
            'status' => 'processing'
        ], 202);
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

    public function checkStatus($jobId)
    {
        $record = ProcessedImages::find($jobId);

        if (!$record) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json([
            'job_id' => $record->id,
            'status' => $record->status,
            // 'status' => 'completed',
            'result_url' => $record->result_url,
            'original_url' => $record->original_url,
        ], 200);
    }

}
