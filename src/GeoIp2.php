<?php

declare(strict_types=1);

namespace PreemStudio\GeoIp2;

use GeoIp2\Database\Reader;
use GeoIp2\Model\City;
use GeoIp2\Model\Country;

class GeoIp2
{
    private Reader $readerCity;

    private Reader $readerCountry;

    public function __construct(array $config)
    {
        $this->readerCity    = new Reader($config['city']);
        $this->readerCountry = new Reader($config['country']);
    }

    public function city($ip): City
    {
        return $this->readerCity->city($ip);
    }

    public function country($ip): Country
    {
        return $this->readerCountry->country($ip);
    }
}
