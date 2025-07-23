<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes([
            'middleware' => ['api', 'auth:sanctum'], // 👈 Quan trọng: dùng api, không dùng web
            'prefix' => 'api', // endpoint sẽ là /api/broadcasting/auth
        ]);

        require base_path('routes/channels.php');
    }
}
