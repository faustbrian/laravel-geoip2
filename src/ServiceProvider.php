<?php

declare(strict_types=1);

namespace PreemStudio\GeoIp2;

use PreemStudio\Jetpack\Package\AbstractServiceProvider;

final class ServiceProvider extends AbstractServiceProvider
{
    public function packageRegistered(): void
    {
        $this->app->singleton('geoip2', fn ($app) => new GeoIp2($app->config['laravel-geoip2']));
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__ . '/../data/GeoLite2-City.mmdb'    => storage_path('app/GeoIp2/GeoLite2-City.mmdb'),
            __DIR__ . '/../data/GeoLite2-Country.mmdb' => storage_path('app/GeoIp2/GeoLite2-Country.mmdb'),
        ], 'data');
    }
}
