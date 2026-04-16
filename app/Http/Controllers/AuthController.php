<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Mail\PasswordResetMail;
use App\Models\Plan;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        // COOKIE LOGIC: Logs the user into the session
        Auth::login($user);
        $email = $request->email;
        // 1. Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        // 2. Store in Cache for 10 minutes (keyed by email)
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));
        // 3. Send the Email
        Mail::to($email)->send(new OtpMail($otp));

        // TOKEN LOGIC: For your future Mobile App
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Registration successful! Please check your email for the verification code to unlock full features.',
            'user' => $user,
            'access_token' => $token, // Keep for mobile
            'requires_verification' => true,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // TOKEN LOGIC: For your future Mobile App
            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully',
                'user' => Auth::user(),
                // 'access_token' => $token, // Keep for mobile

            ], 200);
        }
        return response()->json(['message' => 'Invalid credentials'], 422);
    }



    public function logout(Request $request)
    {
        Auth::guard('web')->logout(); // Clears the cookie
        $request->user()->currentAccessToken()->delete(); // Clears the token
        return response()->json(['message' => 'Logged out']);
    }
    public function verifyOtp(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = $request->user();
        $email = $user->email;

        // 2. Retrieve the OTP from Cache
        $storedOtp = Cache::get('otp_' . $email);

        // 3. Compare and Verify
        if (!$storedOtp || $storedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'The code is invalid or has expired. Please request a new one.'
            ], 422);
        }

        // 4. Success: Mark email as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        // 5. Clear the Cache so the OTP can't be used again
        Cache::forget('otp_' . $email);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully! You can now use all AI Features.',
            'user' => $user // Send updated user to refresh React state
        ], 200);
    }
    public function resendOtp(Request $request)
    {
        $user = Auth::user();
        $email = $user->email;

        // 1. Generate a new 6-digit OTP
        $otp = rand(100000, 999999);

        // 2. Store in Cache for 10 minutes (keyed by email)
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        // 3. Send the Email
        Mail::to($email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent to your email.'
        ], 200);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if we already sent an OTP recently to prevent spam
        if (Cache::has('password_otp_' . $request->email)) {
            return response()->json([
                'message' => 'A code was already sent. Please wait before requesting another.'
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        // Security: Masking the user existence
        if (!$user) {
            return response()->json(['message' => 'If that email is registered, you will receive an OTP shortly.'], 200);
        }

        $otp = rand(100000, 999999);
        Cache::put('password_otp_' . $user->email, $otp, now()->addMinutes(15));

        Mail::to($user->email)->send(new PasswordResetMail($otp));

        return response()->json(['message' => 'If that email is registered, you will receive an OTP shortly.'], 200);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8', // Needs password_confirmation in request
        ]);

        // Retrieve from cache using the email key
        $storedOtp = Cache::get('password_otp_' . $request->email);

        // Verify OTP
        if (!$storedOtp || $storedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 422);
        }

        // Update User
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        // Clear Cache
        Cache::forget('password_otp_' . $request->email);

        return response()->json(['message' => 'Password reset successfully. You can now login.']);
    }

    public function resendForgotPasswordOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // 1. Check if the OTP even exists (15 min window)
        $otp = Cache::get('password_otp_' . $request->email);

        if (!$otp) {
            // If the OTP expired from cache, they need a brand new one
            return $this->forgotPassword($request);
        }

        // 2. Dynamic Cooldown Logic
        $lastSent = Cache::get('last_sent_otp_' . $request->email);

        if ($lastSent) {
            $secondsSinceLastSent = now()->diffInSeconds($lastSent);
            $cooldown = 60; // Your limit

            if ($secondsSinceLastSent < $cooldown) {
                $secondsLeft = $cooldown - $secondsSinceLastSent;

                return response()->json([
                    'message' => "Please wait {$secondsLeft} seconds before requesting another code.",
                    'seconds_left' => $secondsLeft // Sending as a variable for React to use
                ], 429);
            }
        }

        // 3. Resend Logic
        $user = User::where('email', $request->email)->first();

        if ($user) {
            Mail::to($user->email)->send(new PasswordResetMail($otp));

            // Update the "last_sent" timestamp to NOW
            Cache::put('last_sent_otp_' . $request->email, now(), now()->addMinutes(2));
        }

        return response()->json([
            'message' => 'OTP has been resent to your email.',
            'seconds_left' => 60 // Reset the frontend timer
        ]);
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
