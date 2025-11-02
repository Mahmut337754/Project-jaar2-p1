<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Organisator\OrganisatorDashboardController;
use App\Http\Controllers\Verkoper\VerkoperDashboardController;
use App\Http\Controllers\Bezoeker\BezoekerDashboardController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// API Routes for real-time updates
Route::get('/api/events/{event}/tickets/availability', [\App\Http\Controllers\Api\TicketAvailabilityController::class, 'getAvailability'])
    ->name('api.tickets.availability');

// Main dashboard route that redirects based on role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:organisator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
    Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class);
});

// Organisator Routes  
Route::middleware(['auth', 'role:organisator'])->prefix('organisator')->name('organisator.')->group(function () {
    Route::get('/dashboard', [OrganisatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/events', [OrganisatorDashboardController::class, 'events'])->name('events');
    Route::get('/tickets', [OrganisatorDashboardController::class, 'tickets'])->name('tickets');
    Route::get('/stands', [OrganisatorDashboardController::class, 'stands'])->name('stands');
});

// Verkoper Routes
Route::middleware(['auth', 'role:verkoper'])->prefix('verkoper')->name('verkoper.')->group(function () {
    Route::get('/dashboard', [VerkoperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stands', [VerkoperDashboardController::class, 'stands'])->name('stands');
    Route::get('/bookings', [VerkoperDashboardController::class, 'bookings'])->name('bookings');
});

// Bezoeker Routes - Accessible to all authenticated users for ticket purchasing
Route::middleware(['auth'])->prefix('bezoeker')->name('bezoeker.')->group(function () {
    Route::get('/tickets', [\App\Http\Controllers\Bezoeker\TicketPurchaseController::class, 'index'])->name('tickets');
    Route::get('/events/{event}/tickets', [\App\Http\Controllers\Bezoeker\TicketPurchaseController::class, 'show'])->name('tickets.show');
    Route::get('/events/{event}/tickets/{ticket}/purchase', [\App\Http\Controllers\Bezoeker\TicketPurchaseController::class, 'purchase'])->name('tickets.purchase');
    Route::post('/events/{event}/tickets/{ticket}/purchase', [\App\Http\Controllers\Bezoeker\TicketPurchaseController::class, 'store'])->name('tickets.store');
});

// Favorites Routes - Accessible to all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{event}/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Bezoeker Dashboard Routes - Restricted to bezoeker role only
Route::middleware(['auth', 'role:bezoeker'])->prefix('bezoeker')->name('bezoeker.')->group(function () {
    Route::get('/dashboard', [BezoekerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets/my-tickets', [\App\Http\Controllers\Bezoeker\TicketPurchaseController::class, 'myTickets'])->name('tickets.my-tickets');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Voeg deze onderaan of op een logische plek toe:
Route::resource('sellers', SellerController::class);


require __DIR__.'/auth.php';
