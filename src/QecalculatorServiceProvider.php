<?php

namespace Odboxxx\LaravelQecalculator;

use Illuminate\Support\ServiceProvider;

class QecalculatorServiceProvider extends ServiceProvider
{
    public function boot()
    {   
        $this->loadRoutesFrom(__DIR__ . '/../routes/qecalculator.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'qecalculator');
    
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }   
}