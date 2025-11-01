<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Organisator\OrganisatorDashboardController;
use App\Http\Controllers\Verkoper\VerkoperDashboardController;
use App\Http\Controllers\Bezoeker\BezoekerDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerkoperController;

Route::get('/', function () {
    return view('home');
});

// Overzicht van verkopers
Route::get('/verkopers', [VerkoperController::class, 'index'])->name('verkopers.index');

// Wis alle verkopers
Route::post('/verkopers/wis-alles', [VerkoperController::class, 'wisAlles'])->name('verkopers.wisAlles');

// Herstel alle verkopers
Route::post('/verkopers/herstel-alles', [VerkoperController::class, 'herstelAlles'])->name('verkopers.herstelAlles');


Route::get('/login', function () {
    return 'Login pagina placeholder';
})->name('login');

Route::get('/register', function () {
    return 'Register pagina placeholder';
})->name('register');

Route::get('/dashboard', function () {
    return 'Dashboard placeholder';
})->name('dashboard');

Route::get('/admin/dashboard', function () {
    return 'Admin Dashboard placeholder';
})->name('admin.dashboard');

Route::get('/organisator/dashboard', function () {
    return 'Organisator Dashboard placeholder';
})->name('organisator.dashboard');

Route::get('/verkoper/dashboard', function () {
    return 'Verkoper Dashboard placeholder';
})->name('verkoper.dashboard');

Route::get('/bezoeker/dashboard', function () {
    return 'Bezoeker Dashboard placeholder';
})->name('bezoeker.dashboard');

