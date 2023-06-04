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
    private Reader $readerASN;

    private Reader $readerCity;

    private Reader $readerCountry;

    public function __construct()
    {
        $this->readerASN = new Reader($this->getPath('GeoLite2-ASN'));
        $this->readerCity = new Reader($this->getPath('GeoIP2-City'));
        $this->readerCountry = new Reader($this->getPath('GeoLite2-Country'));
    }

    public function asn($ip): Asn
    {
        return $this->readerASN->asn($ip);
    }

    public function city($ip): City
    {
        return $this->readerCity->city($ip);
    }

    public function country($ip): Country
    {
        return $this->readerCountry->country($ip);
    }

    private function getPath(string $edition): string
    {
        return \sprintf('%s/%s', Config::get('geoip2.storage_path'), $edition);
    }
}
