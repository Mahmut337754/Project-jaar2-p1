<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerkoperController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verkopers', [VerkoperController::class, 'index'])->name('verkopers.index');