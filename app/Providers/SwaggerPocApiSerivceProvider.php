<?php

namespace App\Providers;

use Appkr\SwaggerPocApi\Configuration;
use Appkr\SwaggerPocApi\Service\AuthApi;
use Appkr\SwaggerPocApi\Service\ProductApi;
use Appkr\SwaggerPocApi\Service\ReviewApi;
use GuzzleHttp\Client;
use Laravel\Lumen\Application;
use Illuminate\Support\ServiceProvider;

class SwaggerPocApiServiceProvider extends ServiceProvider
{
    // @see http://docs.guzzlephp.org/en/stable/request-options.html#timeout
    const DEFAULT_REQUEST_TIMEOUT = 5;

    public function register()
    {
        $this->bindConfiguration();
        $this->bindAuthApi();
        $this->bindProductApi();
        $this->bindReviewApi();
    }

    private function bindConfiguration()
    {
        $this->app->singleton(
            Configuration::class,
            function (Application $app) {
                $apiServerUrl = env('SWAGGER_POC_API_SERVER_URL', 'http://localhost');
                $apiConfig = new Configuration();
                $apiConfig->setHost($apiServerUrl);

                return $apiConfig;
            }
        );
    }

    private function bindAuthApi()
    {
        $this->app->singleton(
            AuthApi::class,
            function (Application $app) {
                $httpClient = new Client([
                    'timeout' => self::DEFAULT_REQUEST_TIMEOUT
                ]);
                $apiConfig = $app->make(Configuration::class);

                return new AuthApi($httpClient, $apiConfig);
            }
        );
    }

    private function bindProductApi()
    {
        $this->app->singleton(
            ProductApi::class,
            function (Application $app) {
                $httpClient = new Client([
                    'timeout' => self::DEFAULT_REQUEST_TIMEOUT
                ]);
                $apiConfig = $app->make(Configuration::class);

                return new ProductApi($httpClient, $apiConfig);
            }
        );
    }

    private function bindReviewApi()
    {
        $this->app->singleton(
            ReviewApi::class,
            function (Application $app) {
                $httpClient = new Client([
                    'timeout' => self::DEFAULT_REQUEST_TIMEOUT
                ]);
                $apiConfig = $app->make(Configuration::class);

                return new ReviewApi($httpClient, $apiConfig);
            }
        );
    }
}
