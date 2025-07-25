<?php

use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group([
    'prefix' => 'v1',
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register'] );

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        // Routes for user profile
        Route::get('/user', [UserController::class, 'getUser']);
        Route::post('/user/avatar', [UserController::class, 'updateAvatar']);

        // Routes for conversations, messages, reactions, and attachments
        Route::apiResource('conversations', ConversationController::class);
        Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);
        Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);

        Route::post('messages/{message}/reactions', [ReactionController::class, 'store']);
        Route::delete('messages/{message}/reactions', [ReactionController::class, 'destroy']);

        Route::post('messages/{message}/attachments', [AttachmentController::class, 'store']);
    });
});

