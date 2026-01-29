<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ProductJsonImportController;

// Admin Authentication
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/login
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Slider Management
        Route::resource('sliders', SliderController::class);
        
        // Product Management
        Route::resource('products', ProductController::class);
        Route::get('products/import/json', [ProductJsonImportController::class, 'show'])->name('products.import-json');
        Route::post('products/import/json', [ProductJsonImportController::class, 'import'])->name('products.import-json.run');
        Route::resource('product-categories', ProductCategoryController::class);
        
        // Service Management
        Route::resource('services', ServiceController::class);
        
        // Project Management
        Route::resource('projects', ProjectController::class);
        
        // FAQ Management
        Route::resource('faqs', FaqController::class);
        
        // Settings Management
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        
        // Download Center Management
        Route::resource('downloads', DownloadController::class);
        
        // Team Management
        Route::resource('teams', TeamController::class);
    });
});

