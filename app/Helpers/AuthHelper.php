<?php
namespace App\Helpers;

use Symfony\Component\HttpFoundation\Cookie;

class AuthHelper
{
    public static function cookie($token)
    {
        return cookie(
            'access_token',
            $token,
            60 * 24 * 7,
            '/',
            'unemitting-dalilah-inefficaciously.ngrok-free.dev',
            true,   // Secure
            true,   // HttpOnly
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