<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private const OTP_CODE = '1234';

    private const OTP_EXPIRY_MINUTES = 5;

    /**
     * Request OTP for the given phone. OTP is stored and expires in 5 minutes.
     * For now OTP is hardcoded as 1234 (no actual SMS sent).
     */
    public function requestOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
        ]);

        PhoneOtp::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp_code' => self::OTP_CODE,
                'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'OTP sent.',
        ], 200);
    }

    /**
     * Verify OTP and authenticate. Issues Sanctum token.
     * If no user exists for the phone, a new customer user is created.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string'],
        ]);

        $phoneOtp = PhoneOtp::findValidForPhone($request->phone, $request->otp);

        if (! $phoneOtp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP.',
            ], 401);
        }

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'role' => 'customer',
                'password_hash' => null,
            ]
        );

        $phoneOtp->delete();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ],
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Logout (revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();
        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json(['message' => 'Logged out.']);
    }
}
