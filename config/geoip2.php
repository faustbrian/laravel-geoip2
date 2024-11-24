<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

return [
    'license_key' => env('GEOIP2_LICENSE_KEY'),

    'storage_path' => storage_path('app/geoip2'),

    'editions' => ['GeoLite2-ASN', 'GeoLite2-City', 'GeoLite2-Country'],
];
