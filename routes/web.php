<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('kosan/home');
})->name('home');

Route::get('login',[App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::get('admin/login',[App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login',[App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.post');

// Routes admin Middleware
Route::middleware('auth.admin')->group(function () {
    Route::get('admin',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/dashboard',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('admin/logout',[App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
});
