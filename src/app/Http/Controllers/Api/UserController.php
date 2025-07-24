<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Return the authenticated user's information.
     */
    public function getUser(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            return response()->json([
                'user' => $request->user(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to retrieve user.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the authenticated user's avatar.
     *
     * @return JsonResponse
     */
    public function updateAvatar(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $request->validate([
                'avatar' => ['required', 'image', 'max:2048'],
            ]);
            $user = $request->user();
            // Lưu file vào storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
            return response()->json([
                'avatar' => asset('storage/'.$user->avatar),
                'message' => 'Cập nhật avatar thành công!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to update avatar.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
