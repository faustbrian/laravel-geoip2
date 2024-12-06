<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\GeoIp2\Facades;

use Illuminate\Support\Facades\Facade;

final class GeoIp2 extends Facade
{
    #[\Override()]
    protected static function getFacadeAccessor(): string
    {
        return 'geoip2';
    }
}
