<?php

namespace Buatin\Core;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            $request = Http::withHeaders([
                'referer' => request()->root(),
            ])->post('https://admin.buatin.website/api/check', [
                'key' => env('BUATIN_KEY'),
            ]);
            $response = $request->json();
            if ($response['status'] === 500) {
                abort(500);
            }
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
    }
}