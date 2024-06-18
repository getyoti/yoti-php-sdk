<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Yoti\DigitalIdentityClient;

class YotiDigitalIdentityServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DigitalIdentityClient::class, function ($app) {
            $config = $app['config']['yoti'];
            return new DigitalIdentityClient($config['client.sdk.id'], $config['pem.file.path']);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [DigitalIdentityClient::class];
    }
}
