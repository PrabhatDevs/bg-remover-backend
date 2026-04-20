<?php
namespace App\Helpers;

class AuthHelper
{
    public static function cookie($token)
    {
        // return cookie(
        //     'access_token',
        //     $token,
        //     60 * 24 * 7,
        //     '/',
        //     null,   // 🔥 IMPORTANT for ngrok
        //     true,   // Secure
        //     true,   // HttpOnly
        //     false,
        //     'None'  // 🔥 REQUIRED for cross-site
        // );

        return cookie(
            'access_token',
            $token,
            60 * 24 * 7,
            '/',
            'unemitting-dalilah-inefficaciously.ngrok-free.dev', // ✅ EXACT
            true,
            true,
            false,
            'None'
        );
    }

    public static function forgetCookie()
    {
        return cookie(
            'access_token',
            null,
            -1,
            '/',
            'unemitting-dalilah-inefficaciously.ngrok-free.dev',
            true,
            true,
            false,
            'None'
        );
    }
}