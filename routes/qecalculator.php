<?php

use Odboxxx\LaravelQecalculator\Http\Controllers\QecalculatorController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

Route::get('/qecalculator/form', [QecalculatorController::class, 'form'])->name('qecalculator.form');
Route::get('/qecalculator/history', [QecalculatorController::class, 'history'])->name('qecalculator.history');

Route::post('/qecalculator/post', [QecalculatorController::class, 'post'])->name('qecalculator.post');
Route::post('/qecalculator/ajax', [QecalculatorController::class, 'ajax'])->name('qecalculator.ajax');

});