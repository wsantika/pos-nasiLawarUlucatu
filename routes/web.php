<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Kasir\KasirDashboard;

// Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
});

// Route khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

// Route khusus Kasir (Admin juga boleh akses)
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('/kasir', KasirDashboard::class)->name('kasir.dashboard');
});

// Route Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');