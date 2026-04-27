<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\CheckDisposableEmail;
use App\Mail\OtpMail;
use App\Mail\PasswordResetMail;
use App\Models\Plan;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Log;
use Mail;
use Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $domain = substr(strrchr($request->email, "@"), 1);
        if (!checkdnsrr($domain, "MX")) {
            return response()->json([
                'message' => 'Invalid email domain'
            ], 422);
        }
        if (CheckDisposableEmail::isFakeEmail($request->email)) {
            return response()->json([
                'message' => 'Temporary email addresses are not allowed'
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan_type' => 'free',
        ]);

        $email = $user->email;

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        Mail::to($email)->send(new OtpMail($otp));

        // 🔐 Create token
        $token = $user->createToken(
            'auth_token',
            [],
            now()->addHours(6) // ⏱ expires in 6 hours
        )->plainTextToken;

        return response()->json([
            'message' => 'Registration successful!',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // 🔐 Create token
        $token = $user->createToken(
            'auth_token',
            [],
            now()->addHours(6) // ⏱ expires in 6 hours
        )->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }



    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    public function resendOtp(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $email = $user->email;

        // 🔥 Generate NEW OTP (not reuse old)
        $otp = rand(100000, 999999);

        // ⏳ Store OTP (10 mins)
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        // 📧 Send mail
        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email.'
        ], 200);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $email = $user->email;

        $storedOtp = Cache::get('otp_' . $email);

        if (!$storedOtp || $storedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'The code is invalid or has expired.'
            ], 422);
        }

        // ✅ mark verified
        $user->email_verified_at = now();
        $user->save();

        // ✅ clear OTP
        Cache::forget('otp_' . $email);

        // 🔐 OPTIONAL: revoke old tokens (good practice)
        $user->tokens()->delete();

        // 🔐 create fresh token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        $cooldownKey = 'password_otp_cooldown_' . $email;

        // Check 60-second cooldown instead of the 15-minute OTP existence
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'message' => 'Please wait before requesting another code.'
            ], 429);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $otp = rand(100000, 999999);
            // Store OTP for 15 mins
            Cache::put('password_otp_' . $email, $otp, now()->addMinutes(15));
            // Set 60s cooldown
            Cache::put($cooldownKey, true, now()->addSeconds(60));
            // Store timestamp for the "resend" countdown timer in React
            Cache::put('last_sent_otp_' . $email, now(), now()->addMinutes(2));

            Mail::to($user->email)->send(new PasswordResetMail($otp));
        }

        // Always 200 to mask user existence
        return response()->json([
            'message' => 'If that email is registered, you will receive an OTP shortly.'
        ], 200);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' looks for password_confirmation
        ]);

        $storedOtp = Cache::get('password_otp_' . $request->email);

        if (!$storedOtp || $storedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Cleanup: Clear OTP, cooldown, and timestamp
        Cache::forget('password_otp_' . $request->email);
        Cache::forget('password_otp_cooldown_' . $request->email);
        Cache::forget('last_sent_otp_' . $request->email);

        return response()->json(['message' => 'Password reset successfully. You can now login.']);
    }
    public function resendForgotPasswordOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        $cooldownKey = 'password_otp_cooldown_' . $email;

        // ⏱ Check cooldown (60s)
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'message' => 'Please wait before requesting another code.'
            ], 429);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // 🔥 Generate NEW OTP (not reuse old)
            $otp = rand(100000, 999999);

            // ⏳ Store OTP (15 mins)
            Cache::put('password_otp_' . $email, $otp, now()->addMinutes(15));

            // ⏱ Set cooldown
            Cache::put($cooldownKey, true, now()->addSeconds(60));

            // ⏳ Track last sent time (for frontend timer)
            Cache::put('last_sent_otp_' . $email, now(), now()->addMinutes(2));

            // 📧 Send mail
            Mail::to($user->email)->send(new PasswordResetMail($otp));
        }

        // 🔒 Always same response (security)
        return response()->json([
            'message' => 'If that email is registered, you will receive an OTP shortly.'
        ], 200);
    }

    public function getPlans()
    {
        $plans = Plan::all();
        return response()->json([
            'success' => true,
            'plans' => $plans
        ], 200);
    }

    public function googleLogin(Request $request)
    {
        $token = $request->token;

        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $token
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $googleUser = $response->json();

        if (
            !isset($googleUser['email']) ||
            !$googleUser['email_verified']
        ) {
            return response()->json(['error' => 'Email not verified'], 401);
        }

        if ($googleUser['aud'] !== env('GOOGLE_CLIENT_ID')) {
            return response()->json(['error' => 'Invalid client'], 401);
        }

        $user = User::where('email', $googleUser['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser['name'] ?? 'User',
                'email' => $googleUser['email'],
                'password' => bcrypt(str()->random(16)),
                'provider' => 'google'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user
        ])->withCookie(AuthHelper::cookie($token));
    }
}
