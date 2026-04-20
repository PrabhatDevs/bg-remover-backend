<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Mail\OtpMail;
use App\Mail\PasswordResetMail;
use App\Models\Plan;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan_type' => 'free',
        ]);

        $email = $request->email;

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        Mail::to($email)->send(new OtpMail($otp));

        // 🔐 Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful!',
            'user' => $user,
        ], 201)->withCookie(AuthHelper::cookie($token));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to find the user first
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        // REMOVED: $request->session()->regenerate();
        // We are no longer using sessions/cookies, so we don't need this.

        // 🔐 Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'user' => $user,
        ], 200)->withCookie(AuthHelper::cookie($token));
    }



    public function logout(Request $request)
    {

        Log::info('Logout requested');
        // 1. Revoke the token in the database
        // Because your middleware sets the Authorization header, 
        // $request->user() will correctly return the user and their token.
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        // 2. Clear the HttpOnly cookie
        // We send a cookie with the same name but expired (-1)
        $forgetCookie = AuthHelper::forgetCookie(); // Assuming you have this helper method

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200)->withCookie($forgetCookie);
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

        $user->email_verified_at = now();
        $user->save();

        Cache::forget('otp_' . $email);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
            'user' => $user
        ])->withCookie(
                AuthHelper::cookie($token)
            );
    }
    public function resendOtp(Request $request)
    {
        $user = $request->user(); // Authenticated via your Hybrid Cookie Middleware

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        if ($user->email_verified_at) {
            return response()->json(['success' => false, 'message' => 'Email already verified'], 400);
        }

        $email = $user->email;
        $cooldownKey = 'otp_cooldown_' . $email;

        if (Cache::has($cooldownKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait a moment before requesting another code.'
            ], 429);
        }

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));
        Cache::put($cooldownKey, true, now()->addSeconds(60));

        // Mail::to($email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent to your email.'
        ]);
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


}
