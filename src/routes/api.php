<?php

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
        Route::post('/register', 'AuthController@register');

    });
});

