<?php

declare(strict_types=1);

return [
    'license_key' => env('GEOIP2_LICENSE_KEY'),

    'storage_path' => storage_path('app/geoip2'),

    'editions' => ['GeoLite2-ASN', 'GeoLite2-City', 'GeoLite2-Country'],
];
