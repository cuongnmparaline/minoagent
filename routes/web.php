<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('management/login', [AuthController::class, 'getLogin'])->name('login');
Route::post('management/login', [AuthController::class, 'postLogin'])->name('checkLogin');
Route::get('management/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'management', 'middleware' => 'checkLogin'], function(){

    Route::get('/', [HomeController::class, 'dashboard'])->name('management.home');

    // Customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('management.customer');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('management.customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('management.customer.store');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('management.customer.edit');
    Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('management.customer.update');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('management.customer.delete');

    // Account
    Route::get('/account', [AccountController::class, 'index'])->name('management.account');
    Route::get('/account/create', [AccountController::class, 'create'])->name('management.account.create');
    Route::post('/account/store', [AccountController::class, 'store'])->name('management.account.store');
    Route::get('/account/edit/{id}', [AccountController::class, 'edit'])->name('management.account.edit');
    Route::post('/account/update/{id}', [AccountController::class, 'update'])->name('management.account.update');
    Route::get('/account/delete/{id}', [AccountController::class, 'delete'])->name('management.account.delete');

    // Report
    Route::get('/report', [ReportController::class, 'index'])->name('management.report');
    Route::get('/report/create', [ReportController::class, 'create'])->name('management.report.create');
    Route::post('/report/store', [ReportController::class, 'store'])->name('management.report.store');
    Route::get('/report/edit/{id}', [ReportController::class, 'edit'])->name('management.report.edit');
});
