<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Kasir\KasirDashboard;
use App\Livewire\Kasir\PosIndex;

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
    Route::get('/kasir', KasirDashboard::class)->name('kasir.kasir-dashboard');
    Route::get('/pos', PosIndex::class)->name('kasir.pos');
});

// Route Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');