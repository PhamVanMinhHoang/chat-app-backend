<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Services\UserService;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->registerUser($request->validated());

            return response()->json([
                'message' => 'User registered successfully.',
                'user' => $user,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Registration failed.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
