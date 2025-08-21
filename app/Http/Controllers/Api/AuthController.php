<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ResponseFormatter;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register user baru
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user', 
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return ResponseFormatter::success([
            'user'  => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registrasi berhasil', 201);
    }

    /**
     * Login: kembalikan Bearer token
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial tidak valid.'],
            ]);
        }

        // (opsional) revoke token lama jika ingin single-device:
        // $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;

        return ResponseFormatter::success([
            'user'  => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil');
    }

    /**
     * Info user yang sedang login
     */
    public function me(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Profil pengguna');
    }

    /**
     * Logout: hapus token yang sedang dipakai
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success(null, 'Logout berhasil');
    }
}
