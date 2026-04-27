<?php
namespace App\Helpers;

use App\Models\BlockedDomain;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CheckDisposableEmail
{
    public static function isFakeEmail($email)
    {
        $email = strtolower(trim($email));

        // ❌ invalid email safety
        if (!str_contains($email, '@')) {
            return true;
        }

        $domain = substr(strrchr($email, "@"), 1);

        // 🔹 0. Trusted whitelist (SKIP EVERYTHING)
        $whitelist = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];

        if (in_array($domain, $whitelist)) {
            return false; // ✅ always allow, no API call
        }

        // 🔹 1. Check DB (fastest)
        if (BlockedDomain::where('domain', $domain)->exists()) {
            return true;
        }

        // 🔹 2. Check local config
        if (in_array($domain, config('disposable_domains'))) {
            return true;
        }

        // 🔹 3. API check ONLY for non-whitelisted domains
        try {
            $response = Http::timeout(5)->withHeaders([
                'Authorization' => 'Bearer ' . env('CHECKMAIL_API_KEY'),
                'Accept' => 'application/json',
            ])->post('https://api.check-mail.org/v2', [
                        'email' => $email,
                    ]);

            if (!$response->successful()) {
                return false; // fallback safe
            }

            $data = $response->json();

            // 🔥 safer parsing (avoid undefined index)
            $isDisposable = data_get($data, 'data.disposable', false);

            // 🔥 save only if confirmed disposable
            if ($isDisposable) {
                BlockedDomain::firstOrCreate([
                    'domain' => $domain
                ]);
            }

            return $isDisposable;

        } catch (\Exception $e) {
            return false; // API fail → allow
        }
    }
}