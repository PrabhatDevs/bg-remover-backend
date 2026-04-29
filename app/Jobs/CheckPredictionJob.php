<?php

namespace App\Jobs;

use App\Models\ProcessedImages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckPredictionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    public $timeout = 60;
    public $recordId;

    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    public function handle()
    {
       

        $record = ProcessedImages::find($this->recordId);

        if (!$record) {
          
            return;
        }

        if (!$record->prediction_id) {
           
            return;
        }


        if ($this->attempts() > 10) {
          
            $record->update(['status' => 'failed']);
            return;
        }

        $response = Http::withToken(config('services.replicate.token'))
            ->timeout(30)
            ->get("https://api.replicate.com/v1/predictions/{$record->prediction_id}");

       

        if ($response->status() === 429) {
           
            $this->release(10);
            return;
        }

        if (!$response->successful()) {
         
            $this->release(10);
            return;
        }

        $check = $response->json();

      

        if (($check['status'] ?? null) === 'succeeded') {


            $output = $check['output'] ?? null;
            $url = is_array($output) ? $output[0] : $output;

            if (!$url) {
              
                $record->update(['status' => 'failed']);
                return;
            }

           

            $image = Http::timeout(60)->get($url)->body();

            $file = 'processed/' . \Str::random(40) . '.png';

            \Storage::disk('r2')->put($file, $image);

           

            $record->update([
                'status' => 'completed',
                'result_url' => 'https://cdn.toolsbyprabhat.com/' . $file
            ]);

          

            return;
        }

        if (($check['status'] ?? null) === 'failed') {
          
            $record->update(['status' => 'failed']);
            return;
        }

      

        // 🔁 still processing
        self::dispatch($this->recordId)->delay(now()->addSeconds(5));
    }
}