<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use App\Services\EmailDomainValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->validated();

        if (! EmailDomainValidator::allows($data['email'])) {
            return response()->json(['message' => 'Email domain not allowed.'], 422);
        }

        $verificationCode = Str::upper(Str::random(6));

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'student',
            'email_verification_code' => $verificationCode,
        ]);

        return response()->json([
            'message' => 'Registered. Verify your email with the provided code.',
            'verification_code' => $verificationCode,
        ], 201);
    }

    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::query()->where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $request->input('email'))->firstOrFail();

        if ($user->email_verification_code !== $request->input('code')) {
            return response()->json(['message' => 'Invalid verification code.'], 422);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_code' => null,
        ])->save();

        return response()->json(['message' => 'Email verified.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
