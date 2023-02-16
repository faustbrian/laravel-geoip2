<?php

declare(strict_types=1);

namespace PreemStudio\GeoIp2;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-geoip2')
            ->hasConfigFile('laravel-geoip2')
            ->hasInstallCommand(fn (InstallCommand $command) => $command->publishConfigFile());
    }

    public function bootingPackage()
    {
        $this->publishes([
            __DIR__.'/../data/GeoLite2-City.mmdb'    => storage_path('app/GeoIp2/GeoLite2-City.mmdb'),
            __DIR__.'/../data/GeoLite2-Country.mmdb' => storage_path('app/GeoIp2/GeoLite2-Country.mmdb'),
        ], 'data');
    }

    public function register(): void
    {
        $this->app->singleton('geoip2', fn ($app) => new GeoIp2($app->config['laravel-geoip2']));
    }
}
