<?php

use Illuminate\Support\Facades\Route;
use true9\QuickBooks\Http\Controllers\QuickBooksController;

Route::get('/quickbooks/token', [QuickBooksController::class, 'token'])->name('quickbooks.token');
Route::get('/quickbooks/connect', [QuickBooksController::class, 'connect'])->name('quickbooks.connect');
Route::get('/quickbooks/disconnect', [QuickBooksController::class, 'disconnect'])->name('quickbooks.disconnect');
Route::get('/quickbooks/status', [QuickBooksController::class, 'status'])->name('quickbooks.status');
