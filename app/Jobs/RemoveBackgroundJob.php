<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProcessedImages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class RemoveBackgroundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recordId;
    public $originalUrl;

    public function __construct($recordId, $originalUrl)
    {
        $this->recordId = $recordId;
        $this->originalUrl = $originalUrl;
    }

    public function handle()
    {
        $dbRecord = ProcessedImages::find($this->recordId);

        if (!$dbRecord)
            return;


        
        $response = Http::withToken(config('services.replicate.token'))
            ->timeout(60)
            ->connectTimeout(30)
            ->retry(3, 2000)
            ->post('https://api.replicate.com/v1/predictions', [
                'version' => 'a029dff38972b5fda4ec5d75d7d1cd25aeff621d2cf4946a41055d7db66b80bc',
                'input' => [
                    'image' => $this->originalUrl,
                    'format' => 'png',
                    'reverse' => false,
                    'threshold' => 0,
                    'background_type' => 'rgba'
                ],
            ]);

        if ($response->failed()) {
            $dbRecord->update(['status' => 'failed']);
            return;
        }

        $prediction = $response->json();
        $processedUrl = $prediction['output'] ?? null;

        if (!$processedUrl && $prediction['status'] !== 'succeeded') {
            for ($i = 0; $i < 15; $i++) {
                sleep(2);

                $check = Http::withToken(config('services.replicate.token'))
                    ->get("https://api.replicate.com/v1/predictions/{$prediction['id']}")
                    ->json();

                if ($check['status'] === 'succeeded') {
                    $processedUrl = $check['output'];
                    break;
                }

                if ($check['status'] === 'failed') {
                    $dbRecord->update(['status' => 'failed']);
                    return;
                }
            }
        }

        if (!$processedUrl) {
            $dbRecord->update(['status' => 'failed']);
            return;
        }

        $imageContent = file_get_contents($processedUrl);
        $processedFileName = 'processed/' . Str::random(40) . '.png';

        Storage::disk('r2')->put($processedFileName, $imageContent);

        $finalResultUrl = 'https://cdn.toolsbyprabhat.com/' . $processedFileName;

        $dbRecord->update([
            'result_url' => $finalResultUrl,
            'status' => 'completed',
        ]);
    }

   

    // public function handle()
    // {
    //     $dbRecord = ProcessedImages::find($this->recordId);

    //     if (!$dbRecord)
    //         return;

    //     // 🔹 Call Replicate
    //     $response = Http::withToken(config('services.replicate.token'))
    //         ->timeout(60)
    //         ->connectTimeout(30)
    //         ->retry(3, 2000)
    //         ->post('https://api.replicate.com/v1/predictions', [
    //             'version' => 'a029dff38972b5fda4ec5d75d7d1cd25aeff621d2cf4946a41055d7db66b80bc',
    //             'input' => [
    //                 'image' => $this->originalUrl,
    //                 'format' => 'png',
    //                 'reverse' => false,
    //                 'threshold' => 0,
    //                 'background_type' => 'rgba'
    //             ],
    //         ]);

    //     if ($response->failed()) {
    //         $dbRecord->update(['status' => 'failed']);
    //         return;
    //     }

    //     $prediction = $response->json();
    //     $processedUrl = $prediction['output'] ?? null;

    //     // 🔹 Poll if needed
    //     if (!$processedUrl && $prediction['status'] !== 'succeeded') {
    //         for ($i = 0; $i < 15; $i++) {
    //             sleep(2);

    //             $check = Http::withToken(config('services.replicate.token'))
    //                 ->get("https://api.replicate.com/v1/predictions/{$prediction['id']}")
    //                 ->json();

    //             if ($check['status'] === 'succeeded') {
    //                 $processedUrl = $check['output'];
    //                 break;
    //             }

    //             if ($check['status'] === 'failed') {
    //                 $dbRecord->update(['status' => 'failed']);
    //                 return;
    //             }
    //         }
    //     }

    //     if (!$processedUrl) {
    //         $dbRecord->update(['status' => 'failed']);
    //         return;
    //     }

    //     // 🏆 🔥 CLOUDINARY UPLOAD (NO R2 HERE)
    //     \Log::info('Cloudinary upload running');
    //     try {
    //         $upload = Cloudinary::upload($processedUrl, [
    //             'folder' => 'bg-remover',
    //             'transformation' => [
    //                 'quality' => 'auto:eco',   // 🔥 compression
    //                 'fetch_format' => 'auto',  // 🔥 webp/avif
    //             ],
    //         ]);

    //         $optimizedUrl = $upload->getSecurePath();

    //         $dbRecord->update([
    //             'result_url' => $optimizedUrl,
    //             'status' => 'completed',
    //         ]);

    //     } catch (\Exception $e) {
    //         $dbRecord->update(['status' => 'failed']);
    //     }
    // }

    
}