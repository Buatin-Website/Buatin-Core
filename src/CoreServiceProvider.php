<?php

namespace Buatin\Core;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     * @throws GuzzleException
     */
    public function boot(): void
    {
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->safeLoad();

        if (isset($_SERVER['HTTP_HOST']) && $_ENV['APP_ENV'] !== 'local') {
            try {
                $client = new Client([
                    'verify' => false,
                    'headers' => [
                        'referer' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
                    ]
                ]);
                $response = $client->post('https://client.buatin.website/api/check', [
                    'multipart' => [
                        [
                            'name' => 'key',
                            'contents' => $_ENV['BUATIN_KEY']
                        ],
                    ]
                ]);
            } catch (ServerException $e) {
                $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                if ($response['error']['message'] === 'The selected key is invalid.') {
                    abort(500);
                }
            } catch (GuzzleException $e) {
                // Do nothing
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
