<?php

declare(strict_types=1);

namespace BombenProdukt\GeoIp2;

use GeoIp2\Database\Reader;
use GeoIp2\Model\Asn;
use GeoIp2\Model\City;
use GeoIp2\Model\Country;
use Illuminate\Support\Facades\Config;

final class GeoIp2
{
    public function asn($ip): Asn
    {
        return (new Reader($this->getPath('GeoLite2-ASN')))->asn($ip);
    }

    public function city($ip): City
    {
        return (new Reader($this->getPath('GeoLite2-City')))->city($ip);
    }

    public function country($ip): Country
    {
        return (new Reader($this->getPath('GeoLite2-Country')))->country($ip);
    }

    private function getPath(string $edition): string
    {
        return \sprintf('%s/%s', Config::get('geoip2.storage_path'), $edition);
    }
}
