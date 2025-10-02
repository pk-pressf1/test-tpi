<?php

use App\Http\Controllers\ShowsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\BookingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/shows', [ShowsController::class, 'index'])->name('shows.index');
    Route::get('/shows/{id}', [ShowsController::class, 'show'])->name('shows.show');
    Route::get('/events/{id}/seats', [EventsController::class, 'show'])->name('events.seats');
    Route::post('/events/{eventId}/book', [BookingsController::class, 'store'])->name('bookings.store');
});
