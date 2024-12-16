# laravel-qecalculator
Simple quadratic equation calculator for laravel 10 with Blade and Tailwind

## Features

- Sending calculation report by email
- Export calculation results to Excel or CSV
- Calculation export request log
- The ability to download a file with calculations for a specific date

## Download
`````
composer require odboxxx/laravel-qecalculator
`````

## Install
`````
php artisan qecalculator:install
`````
`````
php artisan migrate
`````

## app.css
`````
@tailwind base;
@tailwind components;
@tailwind utilities;
`````

## app.js (optional)
`````
import jQuery from 'jquery';
window.$ = jQuery;
`````

## if vite.config.js, tailwind.config.js exist

Match the following settings

## vite.config.js
`````
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
`````
## tailwind.config.js
`````
const defaultTheme = require('tailwindcss/defaultTheme');
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/odboxxx/laravel-qecalculator/src/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
`````
# Usage
`````
http://yourdomain/qecalculator/form
`````
