<?php

use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Notifications as AdminNotifications;
use App\Livewire\Admin\OrdersManager;
use App\Livewire\Admin\ProductImport;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Customer\Auth\Login as AuthLogin;
use App\Livewire\Customer\Checkout;
use App\Livewire\Customer\Notifications as CustomerNotifications;
use App\Livewire\Customer\Orders;
use App\Livewire\Customer\Products;
use App\Livewire\Customer\Profile;
use App\Livewire\Customer\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', AuthLogin::class)->name('customer.login');

Route::middleware('auth:web')->group(function () {
    Route::get('products', Products::class)->name('customer.products');
    Route::get('checkout', Checkout::class)->name('customer.checkout');
    Route::get('orders', Orders::class)->name('customer.orders');
    Route::get('profile', Profile::class)->name('customer.profile');
    Route::get('settings', Settings::class)->name('customer.settings');
    Route::get('notifications', CustomerNotifications::class)->name('customer.notifications');

    Route::get('logout', function () {
        Auth::logout();

        return redirect()->route('customer.login');
    })->name('customer.logout');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('login', Login::class)->name('login');

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');
        Route::get('products', ProductManager::class)->name('products');
        Route::get('products/import', ProductImport::class)->name('products.import');
        Route::get('orders', OrdersManager::class)->name('orders');
        Route::get('notifications', AdminNotifications::class)->name('notifications');

        Route::get('logout', function () {
            Auth::guard('admin')->logout();

            return redirect()->route('admin.login');
        })->name('logout');
    });
});
