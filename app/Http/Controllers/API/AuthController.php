<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);
        $token = JWTAuth::fromUser($user);

        return sendResponse(['user' => new UserResource($user), 'token' => $token], 'user Created ', 201);

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return sendError('Unauthorized', 401);
        }
        $user = Auth::user();

        return sendResponse(['user' => new UserResource($user), 'token' => $token], 'user logged ', 200);
    }
}
