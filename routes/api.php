<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('client');

Route::middleware('client')->get('/ims/vendor', [VendorController::class, 'index']);
Route::middleware('client')->post('/ims/vendor/create', [VendorController::class, 'store']);