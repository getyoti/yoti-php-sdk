<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Yoti\IDV\IDVClient;

class YotiIDVServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IDVClient::class, function ($app) {
            $config = $app['config']['yoti'];

            return new IDVClient($config['client.sdk.id'], $config['pem.file.path']);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [IDVClient::class];
    }
}
