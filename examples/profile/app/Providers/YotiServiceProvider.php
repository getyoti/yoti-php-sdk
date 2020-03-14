<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Yoti\YotiClient;

class YotiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(YotiClient::class, function ($app) {
            $config = $app['config']['yoti'];
            return new YotiClient($config['client.sdk.id'], $config['pem.file.path']);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [YotiClient::class];
    }
}
