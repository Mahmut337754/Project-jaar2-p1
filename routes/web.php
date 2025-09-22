<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllergeenController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/Allergeen', [AllergeenController::class, 'index'])->name('allergenen.index');
