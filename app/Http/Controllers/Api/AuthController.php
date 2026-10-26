<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // REGISTER: membuat akun baru
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            // password disimpan dalam bentuk hash (tidak bisa dikembalikan ke teks asli)
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Register success',
            'user'    => $user,
        ], 201);
    }

    // LOGIN: memeriksa email + password, lalu membuat token
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // Hash::check membandingkan password yang diketik dengan hash di database
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // membuat token baru untuk pengguna ini
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'    => 'Login success',
            'user' => $user->fresh(),
            'token'      => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // PROFILE: data pemilik token yang sedang dipakai
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // LOGOUT: menghapus token yang dipakai pada request ini
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout success']);
    }
}