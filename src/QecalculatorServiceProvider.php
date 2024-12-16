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
    public function register()
	{
        if (file_exists(base_path('config/qecalculator.php'))) {
            $this->mergeConfigFrom(
                base_path('config/qecalculator.php'),'qecalculator'
            );
        } else {
            $this->mergeConfigFrom(
                __DIR__.'/../config/qecalculator.php','qecalculator'
            );
        }

        $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
    }
}