<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register customer baru.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),

            // User tidak boleh menentukan role sendiri.
            'role' => 'customer',
        ]);

        $token = $user
            ->createToken('tikona-api')
            ->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Login.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email:rfc',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = User::where(
            'email',
            strtolower($validated['email'])
        )->first();

        if (
            !$user ||
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    'Email atau password salah.'
                ],
            ]);
        }

        $token = $user
            ->createToken('tikona-api')
            ->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Logout token yang sedang digunakan.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * User yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}