<?php

declare(strict_types=1);

namespace Tests;

use BaseCodeOy\PackagePowerPack\TestBench\AbstractPackageTestCase;

/**
 * @internal
 */
abstract class TestCase extends AbstractPackageTestCase
{
    protected function getServiceProviderClass(): string
    {
        return \BaseCodeOy\GeoIp2\ServiceProvider::class;
    }
}
