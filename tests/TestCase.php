<?php

namespace IndigoLabo\Franca\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use IndigoLabo\Franca\AppliServiceProvider;
use IndigoLabo\Franca\DatabaseLogServiceProvider;
use IndigoLabo\Franca\ExampleServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            AppliServiceProvider::class,
            DatabaseLogServiceProvider::class,
            ExampleServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
