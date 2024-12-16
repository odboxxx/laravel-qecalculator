<?php

namespace Odboxxx\LaravelQecalculator\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Odboxxx\LaravelQecalculator\QecalculatorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase 
{
    use DatabaseMigrations;

    public function setUp(): void
    {
      parent::setUp();
      
      $this->withoutExceptionHandling();
      $this->withoutVite();
      $this->setUpDatabase();

    }

    protected function getPackageProviders($app)
    {
        return [
            QecalculatorServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/2024_02_05_000001_create_qec_history_table.php');
    }        

}