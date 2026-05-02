<?php

namespace AhmedTaha\FourjawalyPackage\Tests;

use AhmedTaha\FourjawalyPackage\FourJawalyServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            FourJawalyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('fourjawaly.api_key', 'test');
        $app['config']->set('fourjawaly.api_secret', 'test');
        $app['config']->set('fourjawaly.sender_name', 'test');
    }
}