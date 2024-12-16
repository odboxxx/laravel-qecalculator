<?php

namespace Odboxxx\LaravelQecalculator\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsQecalculator
{
    /**
     * Install the Qecalculator.
     *
     * @return int|null
     */
    protected function installQecalculator()
    {

        // Config
        (new Filesystem)->copy(__DIR__.'/../../config/qecalculator.php', base_path('config/qecalculator.php'));

        // Migrations
        $this->components->info('Migration file copy to '.base_path('database/migrations'));
        
        (new Filesystem)->ensureDirectoryExists(base_path('database/migrations'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../database/migrations',base_path('database/migrations'));   

        $this->components->info('Installing and building Node dependencies.');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                'tailwindcss' => '^3.1.0',
                '@tailwindcss/forms' => '^0.5.2',
                'autoprefixer' => '^10.4.2',
                'postcss' => '^8.4.31',
                "jquery" => "^3.7.1",
            ] + $packages;
        });

        $this->runCommands(['npm install', 'npm run build']);

        $this->components->info('tailwind.config, postcss.config, vite.config files copy if not exists');

        if (file_exists(base_path('tailwind.config.js'))===false) {
            copy(__DIR__.'/../../tailwind.config.js', base_path('tailwind.config.js'));
        }
        if (file_exists(base_path('postcss.config.js'))===false) {
            copy(__DIR__.'/../../postcss.config.js', base_path('postcss.config.js'));
        }          
        if (file_exists(base_path('vite.config.js'))===false) {
            copy(__DIR__.'/../../vite.config.js', base_path('vite.config.js'));
        }        

        $this->line('');
        $this->components->info('Qecalculator scaffolding installed successfully.');
    }
}