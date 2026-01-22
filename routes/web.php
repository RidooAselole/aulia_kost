<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KosController;
use App\Http\Controllers\BookingController; // Kita siapkan untuk nanti


Route::get('welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('home');
});

// Route untuk USER klik tombol booking (Proses simpan data)
Route::post('/bookings/store', [BookingController::class, 'store'])->name('booking.store');

// Grouping Route untuk Admin (Kelola Kamar)
Route::prefix('admin')->group(function () {
    Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
    Route::put('/kos/{id}', [KosController::class, 'update'])->name('kos.update');

// Tambahkan ini untuk booking
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
});