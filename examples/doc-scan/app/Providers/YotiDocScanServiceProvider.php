<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Yoti\DocScan\DocScanClient;

class YotiDocScanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DocScanClient::class, function ($app) {
            $config = $app['config']['yoti'];

            return new DocScanClient($config['client.sdk.id'], $config['pem.file.path']);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [DocScanClient::class];
    }
}
