<?php

use Odboxxx\LaravelQecalculator\Http\Controllers\QecalculatorController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

Route::get('/qecalculator/form', [QecalculatorController::class, 'form'])->name('qecalculator.form');
Route::get('/qecalculator/history', [QecalculatorController::class, 'history'])->name('qecalculator.history');
Route::get('/qecalculator/history-export-log', [QecalculatorController::class, 'historyExportLog'])->name('qecalculator.history.exportlog');
Route::get('/qecalculator/history-export-log-download', [QecalculatorController::class, 'historyExportLogDownload'])->name('qecalculator.history.exportlog.download');

Route::post('/qecalculator/post', [QecalculatorController::class, 'post'])->name('qecalculator.post');
Route::post('/qecalculator/ajax', [QecalculatorController::class, 'ajax'])->name('qecalculator.ajax');
Route::post('/qecalculator/history-export', [QecalculatorController::class, 'historyExport'])->name('qecalculator.historyex');

});