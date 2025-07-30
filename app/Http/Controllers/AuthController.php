<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $admin = Admin::where('username', $data['username'])->first();

        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username tidak ditemukan',
            ], 401);
        }

        if (!Hash::check($data['password'], $admin->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah',
            ], 401);
        }

        $token = $admin->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'phone' => $admin->phone,
                    'email' => $admin->email,
                ]
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
        ]);
    }
}
