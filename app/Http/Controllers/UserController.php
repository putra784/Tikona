<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan profile user.
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    /**
     * Update profile user.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'min:2',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'email:rfc',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'password' => [
                'sometimes',
                'string',
                'min:8',
                'max:72',
                'confirmed',
            ],
        ]);

        if (isset($validated['email'])) {
            $validated['email'] =
                strtolower($validated['email']);
        }

        if (isset($validated['password'])) {
            $validated['password'] =
                Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile berhasil diperbarui.',
            'data' => $user->fresh(),
        ]);
    }
}