<?php

namespace true9\QuickBooks\Providers;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use true9\QuickBooks\Credentials;
use true9\QuickBooks\QuickBooks;

class QuickBooksServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishes();
        $this->registerRoutes();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/quickbooks.php', 'quickbooks');

        $this->app->bind(Credentials::class, function ($app) {
            return new Credentials($app->make(FilesystemManager::class));
        });

        $this->app->bind(QuickBooks::class, function ($app) {
            $credentials = $app->make(Credentials::class);

            $quickBooks = new QuickBooks();

            if ($credentials->exists()) {
                $quickBooks->setRealmId($credentials->getRealmId());
                $quickBooks->setRefreshToken($credentials->getRefreshToken());
                $quickBooks->setAccessToken($credentials->getAccessToken());

                if ($credentials->isExpired()) {
                    $credentials->store($quickBooks->getAccessToken());
                }
            }

            return $quickBooks;
        });
    }

    protected function registerPublishes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/quickbooks.php' => config_path('quickbooks.php'),
            ], 'config');
        }
    }

    protected function registerRoutes()
    {
        Route::group([
            'prefix' => 'api/quickbooks',
            'middleware' => ['api', 'auth:sanctum']
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        });

        Route::group([
            'prefix' => 'quickbooks',
            // 'middleware' => ['auth']
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }
}
