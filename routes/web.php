<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/list', [CartController::class, 'list'])->name('cart.list'); // New API endpoint
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Favorites
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::get('/favorites/list', [FavoriteController::class, 'list'])->name('favorites.list');
Route::get('/favorites/count', [FavoriteController::class, 'count'])->name('favorites.count');
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

// User Dashboard
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('products/{product}/duplicate', [\App\Http\Controllers\Admin\ProductController::class, 'duplicate'])->name('admin.products.duplicate');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
    Route::resource('sections', \App\Http\Controllers\Admin\SectionController::class)->names('admin.sections');
    Route::patch('orders/{order}/batch-update', [\App\Http\Controllers\Admin\OrderController::class, 'batchUpdate'])->name('admin.orders.batch-update');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->names('admin.orders');
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->names('admin.faqs');
    Route::patch('orders/{order}/items/{item}', [\App\Http\Controllers\Admin\OrderController::class, 'updateItem'])->name('admin.orders.items.update');
    Route::post('orders/{order}/items/bulk-update', [\App\Http\Controllers\Admin\OrderController::class, 'bulkUpdateItems'])->name('admin.orders.items.bulk-update');
    Route::post('orders/bulk-update', [\App\Http\Controllers\Admin\OrderController::class, 'bulkUpdate'])->name('admin.orders.bulk-update');
    Route::post('orders/bulk-delete', [\App\Http\Controllers\Admin\OrderController::class, 'bulkDestroy'])->name('admin.orders.bulk-delete');

    // Users
    Route::patch('users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('admin.users.update-status');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
    Route::post('users/bulk-delete', [\App\Http\Controllers\Admin\UserController::class, 'bulkDestroy'])->name('admin.users.bulk-delete');

    // Payment Gateways
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'store'])->name('admin.payments.store');
    Route::put('/payments/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'update'])->name('admin.payments.update');
    Route::patch('/payments/{gateway}/toggle', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggle'])->name('admin.payments.toggle');
    Route::delete('/payments/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'destroy'])->name('admin.payments.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
    Route::delete('/settings/image', [\App\Http\Controllers\Admin\SettingController::class, 'deleteImage'])->name('admin.settings.delete-image');

    // Reports & Analytics
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.reports.export');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/api/reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('admin.api.reports.sales');
    Route::get('/api/reports/products', [\App\Http\Controllers\Admin\ReportController::class, 'productSales'])->name('admin.api.reports.products');

    // Notifications
    Route::get('/notifications/logs', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::delete('/notifications/logs', [\App\Http\Controllers\Admin\NotificationController::class, 'clearLogs'])->name('admin.notifications.clear');
    Route::post('/notifications/bulk-delete', [\App\Http\Controllers\Admin\NotificationController::class, 'bulkDelete'])->name('admin.notifications.bulk-delete');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');

    // Activity Log Clearing
    Route::delete('/activity-logs/clear', [\App\Http\Controllers\Admin\AdminController::class, 'clearActivityLogs'])->name('admin.activity-logs.clear');
});

// Orders
Route::middleware('auth')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/read', [ProfileController::class, 'markNotificationRead'])->name('notifications.read');
    
    // User Notifications Management
    Route::get('/user/notifications', [\App\Http\Controllers\User\NotificationController::class, 'index'])->name('user.notifications.index');
    Route::post('/user/notifications/mark-read', [\App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('user.notifications.mark-read');
    Route::delete('/user/notifications/bulk-destroy', [\App\Http\Controllers\User\NotificationController::class, 'bulkDestroy'])->name('user.notifications.bulk-destroy');
    Route::delete('/user/notifications/clear-all', [\App\Http\Controllers\User\NotificationController::class, 'clearAll'])->name('user.notifications.clear-all');
    Route::delete('/user/notifications/{id}', [\App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('user.notifications.destroy');
    Route::get('/notifications/check', [\App\Http\Controllers\User\NotificationController::class, 'check'])->name('user.notifications.check');
});

// Admin Notification Check
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/notifications/check', [\App\Http\Controllers\Admin\NotificationController::class, 'check'])->name('admin.notifications.check');
});

// Pages
Route::get('/about-us', [\App\Http\Controllers\PageController::class, 'about'])->name('pages.about');
Route::get('/store-locator', [\App\Http\Controllers\PageController::class, 'storeLocator'])->name('pages.store-locator');
Route::get('/shipping-policy', [\App\Http\Controllers\PageController::class, 'shippingPolicy'])->name('pages.shipping-policy');
Route::get('/returns-policy', [\App\Http\Controllers\PageController::class, 'returnsPolicy'])->name('pages.returns');
Route::get('/privacy-policy', [\App\Http\Controllers\PageController::class, 'privacyPolicy'])->name('pages.privacy');

require __DIR__.'/auth.php';
