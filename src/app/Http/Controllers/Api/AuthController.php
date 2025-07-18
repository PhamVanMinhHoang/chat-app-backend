<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

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

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $result = $this->userService->loginUser($data);
            return response()->json([
                'message' => 'Login successful.',
                'user' => $result->user,
                'token' => $result->token,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Login failed.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
