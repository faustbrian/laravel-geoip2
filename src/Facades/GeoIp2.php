<?php

declare(strict_types=1);

namespace BaseCodeOy\GeoIp2\Facades;

use Illuminate\Support\Facades\Facade;

final class GeoIp2 extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'geoip2';
    }
}
