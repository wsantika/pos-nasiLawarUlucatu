<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ProductIndex;
use App\Livewire\Admin\CategoryIndex;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Kasir\KasirDashboard;
use App\Livewire\Kasir\PosIndex;

// Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
});

// Route khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/products', ProductIndex::class)->name('admin.product');
    Route::get('/category', CategoryIndex::class)->name('admin.category');
    Route::get('/transaction', TransactionIndex::class)->name('admin.transaction');
    Route::get('/dashboard/report/pdf/daily', [App\Http\Controllers\Admin\DashboardReportController::class, 'daily'])->name('admin.dashboard.pdf.daily');
    Route::get('/dashboard/report/pdf/monthly', [App\Http\Controllers\Admin\DashboardReportController::class, 'monthly'])->name('admin.dashboard.pdf.monthly');
});

// Route khusus Kasir (Admin juga boleh akses)
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::get('/kasir', KasirDashboard::class)->name('kasir.dashboard');
    Route::get('/pos', PosIndex::class)->name('kasir.pos');

});

// Route Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');