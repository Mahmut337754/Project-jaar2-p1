<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerkoperController;

Route::get('/', function () {
    return view('welcome');
});

// Overzicht van verkopers
Route::get('/verkopers', [VerkoperController::class, 'index'])->name('verkopers.index');

// Wis alle verkopers
Route::post('/verkopers/wis-alles', [VerkoperController::class, 'wisAlles'])->name('verkopers.wisAlles');

// Herstel alle verkopers
Route::post('/verkopers/herstel-alles', [VerkoperController::class, 'herstelAlles'])->name('verkopers.herstelAlles');
