<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
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

Route::view('forbidden', 'errors.forbidden');

// Auth routes (login, register, logout)
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Route::get('profile', [ProfileController::class, 'index']);
// Route::get('profile/edit', [ProfileController::class, 'edit']);
// Route::post('profile/update', [ProfileController::class, 'update']);
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
Route::post('profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
Route::delete('profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');

Route::get('transaksi', [TransactionController::class, 'index']);

// Master Data
// CRUD Customer
Route::get('customer', [CustomerController::class, 'index']);
Route::get('customer/show/{id}', [CustomerController::class, 'show']);
Route::get('customer/create', [CustomerController::class, 'create']);
Route::post('customer/save', [CustomerController::class, 'save']);
Route::get('customer/edit/{id}', [CustomerController::class, 'edit']);
Route::post('customer/update/{id}', [CustomerController::class, 'update']);
Route::get('customer/destroy/{id}', [CustomerController::class, 'destroy']);

// CRUD Supplier
Route::get('supplier', [SupplierController::class, 'index']);
Route::get('supplier/show/{id}', [SupplierController::class, 'show']);
Route::get('supplier/create', [SupplierController::class, 'create']);
Route::post('supplier/save', [SupplierController::class, 'save']);
Route::get('supplier/edit/{id}', [SupplierController::class, 'edit']);
Route::post('supplier/update/{id}', [SupplierController::class, 'update']);
Route::get('supplier/destroy/{id}', [SupplierController::class, 'destroy']);

// CRUD Barang
Route::get('barang', [ItemController::class, 'index']);
Route::get('barang/show/{id}', [ItemController::class, 'show']);
Route::get('barang/create', [ItemController::class, 'create']);
Route::post('barang/save', [ItemController::class, 'save']);
Route::get('barang/edit/{id}', [ItemController::class, 'edit']);
Route::post('barang/update/{id}', [ItemController::class, 'update']);
Route::get('barang/destroy/{id}', [ItemController::class, 'destroy']);
Route::post('satuan/ajax-save', [ItemController::class, 'ajaxSaveSatuan']);
Route::post('jenis-barang/ajax-save', [ItemController::class, 'ajaxSaveJenis']);
Route::post('group-barang/ajax-save', [ItemController::class, 'ajaxSaveGroup']);

// Proses
// Purchase Order
Route::get('po', [PrePurchaseController::class, 'index']);
Route::get('po/create', [PrePurchaseController::class, 'create']);
Route::post('po/save', [PrePurchaseController::class, 'save']);
Route::get('po/edit/{id}', [PrePurchaseController::class, 'edit']);
Route::get('po/show/{id}', [PrePurchaseController::class, 'show']);
Route::post('po/update/{id}', [PrePurchaseController::class, 'update']);
Route::get('po/destroy/{id}', [PrePurchaseController::class, 'destroy']);
Route::get('po/print/{id}', [PrePurchaseController::class, 'print']);
Route::get('po/generateNoPO', [PrePurchaseController::class, 'generateNoPOAjax']);
Route::post('po/markSelesai/{id}', [PrePurchaseController::class, 'markSelesai']);
Route::get('po/export', [PrePurchaseController::class, 'export']);

// Pembelian
Route::get('pembelian', [PurchaseController::class, 'index']);
Route::get('pembelian/show/{id}', [PurchaseController::class, 'show']);
Route::get('pembelian/create', [PurchaseController::class, 'create']);
Route::post('pembelian/save', [PurchaseController::class, 'save']);
Route::get('pembelian/edit/{id}', [PurchaseController::class, 'edit']);
Route::post('pembelian/update/{id}', [PurchaseController::class, 'update']);
Route::get('pembelian/destroy/{id}', [PurchaseController::class, 'destroy']);
Route::get('pembelian/generateNoFakturAjax', [PurchaseController::class, 'generateNoFakturAjax']);
Route::get('pembelian/export', [PurchaseController::class, 'export']);

// Penjualan
Route::get('penjualan', [SalesController::class, 'index']);
Route::get('penjualan/show/{id}', [SalesController::class, 'show']);
Route::get('penjualan/create', [SalesController::class, 'create']);
Route::post('penjualan/save', [SalesController::class, 'save']);
Route::get('penjualan/edit/{id}', [SalesController::class, 'edit']);
Route::post('penjualan/update/{id}', [SalesController::class, 'update']);
Route::get('penjualan/destroy/{id}', [SalesController::class, 'destroy']);
Route::get('penjualan/generateNoNotaAjax', [SalesController::class, 'generateNoNotaAjax']);
Route::get('penjualan/export', [SalesController::class, 'export']);

// User Management
Route::prefix('superadmin')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/create', [UserController::class, 'create']);
    Route::post('users/save', [UserController::class, 'save']);
    Route::get('users/edit/{id}', [UserController::class, 'edit']);
    Route::post('users/update/{id}', [UserController::class, 'update']);
    Route::get('users/destroy/{id}', [UserController::class, 'destroy']);
    Route::get('users/show/{id}', [UserController::class, 'show']);
});

// Satuan Konversi
Route::get('satuan-konversi', [UnitConversionController::class, 'index']);
Route::get('satuan-konversi/create', [UnitConversionController::class, 'create']);
Route::post('satuan-konversi/save', [UnitConversionController::class, 'save']);
Route::get('satuan-konversi/edit/{id}', [UnitConversionController::class, 'edit']);
Route::post('satuan-konversi/update/{id}', [UnitConversionController::class, 'update']);
Route::get('satuan-konversi/destroy/{id}', [UnitConversionController::class, 'destroy']);

// Satuan
Route::get('satuan', [UnitController::class, 'index']);
Route::get('satuan/create', [UnitController::class, 'create']);
Route::post('satuan/save', [UnitController::class, 'save']);
Route::get('satuan/edit/{id}', [UnitController::class, 'edit']);
Route::post('satuan/update/{id}', [UnitController::class, 'update']);
Route::get('satuan/destroy/{id}', [UnitController::class, 'destroy']);

// Jenis Barang
Route::get('jenis-barang', [ItemCategoryController::class, 'index']);
Route::get('jenis-barang/create', [ItemCategoryController::class, 'create']);
Route::post('jenis-barang/save', [ItemCategoryController::class, 'save']);
Route::get('jenis-barang/edit/{id}', [ItemCategoryController::class, 'edit']);
Route::post('jenis-barang/update/{id}', [ItemCategoryController::class, 'update']);
Route::get('jenis-barang/destroy/{id}', [ItemCategoryController::class, 'destroy']);

// Group Barang
Route::get('group-barang', [ItemGroupController::class, 'index']);
Route::get('group-barang/create', [ItemGroupController::class, 'create']);
Route::post('group-barang/save', [ItemGroupController::class, 'save']);
Route::get('group-barang/edit/{id}', [ItemGroupController::class, 'edit']);
Route::post('group-barang/update/{id}', [ItemGroupController::class, 'update']);
Route::get('group-barang/destroy/{id}', [ItemGroupController::class, 'destroy']);

// Logs
Route::prefix('superadmin')->group(function () {
    Route::get('logs', [ActivityLogController::class, 'index']);
    Route::get('logs/create', [ActivityLogController::class, 'create']);
    Route::post('logs/save', [ActivityLogController::class, 'save']);
    Route::get('logs/edit/{id}', [ActivityLogController::class, 'edit']);
    Route::post('logs/update/{id}', [ActivityLogController::class, 'update']);
    Route::get('logs/destroy/{id}', [ActivityLogController::class, 'destroy']);
});

// Settings (Superadmin)
// Route::prefix('superadmin')->group(function () {
//     Route::get('settings', [SettingsController::class, 'index']);
//     Route::post('settings/update', [SettingsController::class, 'update']);
//     Route::get('settings/backup', [SettingsController::class, 'backup']);
// });

Route::resource('sales', SalesController::class);
Route::get('sales/export', [SalesController::class, 'export'])->name('sales.export');
Route::get('sales/generate-invoice-number', [SalesController::class, 'generateInvoiceNumberAjax'])->name('sales.generate-invoice-number');

Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/summary-by-date', [TransactionController::class, 'getSummaryByDateRange'])->name('transactions.summary-by-date');
Route::get('transactions/daily-data', [TransactionController::class, 'getDailyTransactionData'])->name('transactions.daily-data');
Route::get('transactions/top-selling-items', [TransactionController::class, 'getTopSellingItems'])->name('transactions.top-selling-items');
Route::get('transactions/customer-summary', [TransactionController::class, 'getCustomerTransactionSummary'])->name('transactions.customer-summary');
Route::get('transactions/supplier-summary', [TransactionController::class, 'getSupplierTransactionSummary'])->name('transactions.supplier-summary');
Route::get('transactions/export', [TransactionController::class, 'exportSummary'])->name('transactions.export');

Route::resource('units', UnitController::class);
Route::get('units/search', [UnitController::class, 'search'])->name('units.search');
Route::get('units/export', [UnitController::class, 'export'])->name('units.export');
Route::post('units/import', [UnitController::class, 'import'])->name('units.import');
Route::get('units/conversions/{itemId}', [UnitController::class, 'getConversionsByItem'])->name('units.conversions');

Route::resource('users', UserController::class);
Route::post('users/{id}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::get('users/search', [UserController::class, 'search'])->name('users.search');
Route::get('users/export', [UserController::class, 'export'])->name('users.export');