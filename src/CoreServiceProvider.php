<?php

namespace Buatin\Core;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
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
        $dotenv = Dotenv::create(__DIR__);
        $dotenv->safeLoad();

        if ($_ENV['APP_ENV'] !== 'local') {
            $client = new Client([
                'verify' => false,
                'headers' => [
                    'referer' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
                ]
            ]);
            $response = $client->post('https://admin.buatin.website/api/check', [
                'multipart' => [
                    [
                        'name' => 'key',
                        'contents' => $_ENV['BUATIN_KEY']
                    ],
                ]
            ]);

            $response = json_decode($response->getBody(), true);
            if ($response['status'] === 500) {
                die(500);
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
