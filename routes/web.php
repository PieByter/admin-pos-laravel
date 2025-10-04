<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemGroupController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PrePurchaseController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\UnitConversionController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\PurchaseReturnController;

Route::view('/forbidden', 'errors.forbidden')->name('forbidden');

// Auth routes (login, register, logout)
Route::get('/', [AuthController::class, 'login'])->name('home');
Route::prefix('auth')->name('auth.')->group(function () {
    Route::match(['GET', 'POST'], 'login', [AuthController::class, 'login'])->name('login');
    Route::match(['GET', 'POST'], 'register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('upload-photo', [ProfileController::class, 'uploadPhoto'])->name('upload-photo');
        Route::delete('remove-photo', [ProfileController::class, 'removePhoto'])->name('remove-photo');
    });

    // Transaction summary
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('summary-by-date', [TransactionController::class, 'getSummaryByDateRange'])->name('summary-by-date');
        Route::get('daily-data', [TransactionController::class, 'getDailyTransactionData'])->name('daily-data');
        Route::get('top-selling-items', [TransactionController::class, 'getTopSellingItems'])->name('top-selling-items');
        Route::get('customer-summary', [TransactionController::class, 'getCustomerTransactionSummary'])->name('customer-summary');
        Route::get('supplier-summary', [TransactionController::class, 'getSupplierTransactionSummary'])->name('supplier-summary');
        Route::get('export', [TransactionController::class, 'exportSummary'])->name('export');
    });

    // Master Data - Customers
    Route::resource('customers', CustomerController::class);

    // Master Data - Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Master Data - Items
    Route::resource('items', ItemController::class);
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('search', [ItemController::class, 'search'])->name('search');
        Route::get('export', [ItemController::class, 'export'])->name('export');
        Route::post('import', [ItemController::class, 'import'])->name('import');
        // Route::post('units/ajax-save', [ItemController::class, 'ajaxSaveUnit'])->name('units.ajax-save');
        // Route::post('types/ajax-save', [ItemController::class, 'ajaxSaveType'])->name('types.ajax-save');
        // Route::post('groups/ajax-save', [ItemController::class, 'ajaxSaveGroup'])->name('groups.ajax-save');
        Route::post('units/ajax-save', [UnitController::class, 'ajaxSave'])->name('units.ajax-save');
        Route::post('item-categories/ajax-save', [ItemCategoryController::class, 'ajaxSave'])->name('item-categories.ajax-save');
        Route::post('item-groups/ajax-save', [ItemGroupController::class, 'ajaxSave'])->name('item-groups.ajax-save');
    });

    // Master Data - Units
    Route::resource('units', UnitController::class);
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('search', [UnitController::class, 'search'])->name('search');
        Route::get('export', [UnitController::class, 'export'])->name('export');
        Route::post('import', [UnitController::class, 'import'])->name('import');
        Route::get('conversions/{itemId}', [UnitController::class, 'getConversionsByItem'])->name('conversions');
        Route::post('ajax-save', [UnitController::class, 'ajaxSave'])->name('ajax-save');
    });

    // Master Data - Item Types (Categories)
    Route::resource('item-categories', ItemCategoryController::class);
    Route::post('item-categories/ajax-save', [ItemCategoryController::class, 'ajaxSave'])->name('item-categories.ajax-save');

    // Master Data - Item Groups
    Route::resource('item-groups', ItemGroupController::class);
    Route::post('item-groups/ajax-save', [ItemGroupController::class, 'ajaxSave'])->name('item-groups.ajax-save');

    // Master Data - Unit Conversions
    Route::resource('unit-conversions', UnitConversionController::class);

    // Process - Purchase Orders (PO)
    Route::resource('purchase-orders', PrePurchaseController::class);
    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('{id}/print', [PrePurchaseController::class, 'print'])->name('print');
        Route::get('generate-number', [PrePurchaseController::class, 'generateNumber'])->name('generate-number');
        Route::post('{id}/mark-completed', [PrePurchaseController::class, 'markCompleted'])->name('mark-completed');
        Route::get('export', [PrePurchaseController::class, 'export'])->name('export');
    });

    // Process - Purchases
    Route::resource('purchases', PurchaseController::class);
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('generate-invoice-number', [PurchaseController::class, 'generateInvoiceNumber'])->name('generate-invoice-number');
        Route::get('export', [PurchaseController::class, 'export'])->name('export');
    });

    // Process - Sales
    Route::resource('sales', SalesController::class);
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('generate-invoice-number', [SalesController::class, 'generateInvoiceNumber'])->name('generate-invoice-number');
        Route::get('export', [SalesController::class, 'export'])->name('export');
    });

    // Process - Purchase Returns (Retur Pembelian)
    Route::resource('purchase-returns', PurchaseReturnController::class);
    Route::prefix('purchase-returns')->name('purchase-returns.')->group(function () {
        Route::get('generate-return-number', [PurchaseReturnController::class, 'generateReturnNumber'])->name('generate-return-number');
        Route::get('{id}/print', [PurchaseReturnController::class, 'print'])->name('print');
        Route::get('export', [PurchaseReturnController::class, 'export'])->name('export');
        Route::post('{id}/approve', [PurchaseReturnController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [PurchaseReturnController::class, 'reject'])->name('reject');
    });

    // Process - Sales Returns (Retur Penjualan)
    Route::resource('sales-returns', PurchaseReturnController::class);
    Route::prefix('sales-returns')->name('sales-returns.')->group(function () {
        Route::get('generate-return-number', [PurchaseReturnController::class, 'generateReturnNumber'])->name('generate-return-number');
        Route::get('{id}/print', [PurchaseReturnController::class, 'print'])->name('print');
        Route::get('export', [PurchaseReturnController::class, 'export'])->name('export');
        Route::post('{id}/approve', [PurchaseReturnController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [PurchaseReturnController::class, 'reject'])->name('reject');
    });

    // SuperAdmin only routes
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::prefix('users')->name('users.')->group(function () {
            Route::post('{id}/change-password', [UserController::class, 'changePassword'])->name('change-password');
            Route::post('{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('search', [UserController::class, 'search'])->name('search');
            Route::get('export', [UserController::class, 'export'])->name('export');
        });

        // Activity Logs
        Route::resource('activity-logs', ActivityLogController::class);
    });

    Route::resource('purchase-returns', PurchaseReturnController::class);
    Route::prefix('purchase-returns')->name('purchase-returns.')->group(function () {
        Route::get('generate-return-number', [PurchaseReturnController::class, 'generateReturnNumber'])->name('generate-return-number');
        Route::get('{id}/print', [PurchaseReturnController::class, 'print'])->name('print');
        Route::get('export', [PurchaseReturnController::class, 'export'])->name('export');
        Route::post('{id}/approve', [PurchaseReturnController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [PurchaseReturnController::class, 'reject'])->name('reject');
    });

    Route::resource('sales-returns', SalesReturnController::class);
    Route::prefix('sales-returns')->name('sales-returns.')->group(function () {
        Route::get('generate-return-number', [SalesReturnController::class, 'generateReturnNumber'])->name('generate-return-number');
        Route::get('{id}/print', [SalesReturnController::class, 'print'])->name('print');
        Route::get('export', [SalesReturnController::class, 'export'])->name('export');
        Route::post('{id}/approve', [SalesReturnController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [SalesReturnController::class, 'reject'])->name('reject');
    });
});
