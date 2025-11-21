<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(['token'=>$token,'user'=>new UserResource($user)],201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        try {
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error'=>'Invalid credentials'],401);
            }
        } catch (JWTException $e) {
            return response()->json(['error'=>'Could not create token'],500);
        }
        $token = JWTAuth::fromUser(auth()->user());
        return response()->json(['token'=>$token,'user'=>new UserResource(auth()->user())]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Logged out successfully']);
    }

    public function me()
    {
        return response()->json(new UserResource(auth()->user()));
    }
}

