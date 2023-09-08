<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();

        /** @var \App\Models\User $user */
        $user = User::create($data);
        $token = $user->createToken('ACCESS_TOKEN')->plainTextToken;
        return response([
            'message' => 'User add sucufuly',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Provide email and password not incorrect'
            ], 422);
        }

        /** @var \App\Models\User */
        $user = Auth::user();
        $token = $user->createToken('ACCESS_TOKEN')->plainTextToken;

        return response([
            'message' => 'Your are login sucufuly',
            'user' => $user,
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response([
            'message' => 'Your are logout',
        ], 204);
    }
}
