<?php

use Illuminate\Support\Facades\Route;
use true9\QuickBooks\Http\Controllers\QuickBooksController;

Route::get('/token', [QuickBooksController::class, 'token'])->name('quickbooks.token');
Route::get('/connect', [QuickBooksController::class, 'connect'])->name('quickbooks.connect');
Route::get('/disconnect', [QuickBooksController::class, 'disconnect'])->name('quickbooks.disconnect');
