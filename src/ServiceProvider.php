<?php

declare(strict_types=1);

namespace BombenProdukt\GeoIp2;

use BombenProdukt\PackagePowerPack\Package\AbstractServiceProvider;

final class ServiceProvider extends AbstractServiceProvider
{
    public function packageRegistered(): void
    {
        $this->app->singleton('geoip2', GeoIp2::class);
    }
}
