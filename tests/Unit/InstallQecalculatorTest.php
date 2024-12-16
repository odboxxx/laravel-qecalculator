<?php

namespace Odboxxx\LaravelQecalculator\Tests\Unit;

use Illuminate\Support\Facades\Artisan;

use Odboxxx\LaravelQecalculator\Tests\TestCase;

class InstallQecalculatorTest extends TestCase
{
    /** @test */
    function the_install_command()
    {

        Artisan::call('qecalculator:install');
        $this->assertTrue(true);
    }
}