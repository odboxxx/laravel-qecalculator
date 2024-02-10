<?php

namespace Odboxxx\LaravelQecalculator\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Odboxxx\LaravelQecalculator\QecalculatorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase 
{
    use RefreshDatabase;

    public function setUp(): void
    {
      parent::setUp();
      // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            QecalculatorServiceProvider::class,
        ];
    }

}