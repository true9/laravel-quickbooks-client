<?php

use Illuminate\Support\Facades\Route;
use true9\QuickBooks\Http\Controllers\QuickBooksController;

Route::get('/status', [QuickBooksController::class, 'status'])->name('quickbooks.status');
