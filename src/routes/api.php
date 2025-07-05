<?php

use App\Http\Controllers\Api\AuthController;
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
        Route::post('/login', 'AuthController@login');
        Route::post('/register', [AuthController::class, 'register'] );

        Route::middleware('auth:api')->group(function () {
            Route::post('/logout', 'AuthController@logout');
            Route::get('/user', 'AuthController@user');
        });
    });
});

