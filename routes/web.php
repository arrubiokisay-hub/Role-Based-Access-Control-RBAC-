<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserItemController;

// === Public Routes (No Authentication Needed) ===
Route::get('/', function () {
    return view('portal');
})->name('portal');

Route::get('/user/login', [AuthController::class, 'showUserLogin'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login.submit');

Route::get('/user/register', [AuthController::class, 'showRegister'])->name('user.register');
Route::post('/user/register', [AuthController::class, 'register'])->name('user.register.submit');

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::get('/contact-admin', function () {
    return view('contact-admin');
})->name('contact.admin');

// Logout actions
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');


// === Admin-Only Routes (Protected by 'auth' and 'role:admin') ===
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/items/{id}/status', [AdminDashboardController::class, 'updateStatus'])->name('admin.items.status');
    Route::delete('/admin/items/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.items.delete');
    Route::get('/admin/item/{id}/history', [AdminDashboardController::class, 'viewHistory'])->name('admin.item.history');
});


Route::middleware(['role:admin,user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/user/report-lost', [UserDashboardController::class, 'reportLost'])->name('user.report.lost');
    Route::post('/user/report-lost', [UserDashboardController::class, 'submitReportLost'])->name('user.report.lost.submit');

    Route::get('/user/report-found', [UserDashboardController::class, 'reportFound'])->name('user.report.found');
    Route::post('/user/report-found', [UserDashboardController::class, 'submitReportFound'])->name('user.report.found.submit');

    Route::get('/user/items/{id}', [UserDashboardController::class, 'showItem'])->name('user.items.show');
    Route::delete('/user/items/{id}/undo', [UserDashboardController::class, 'undoPost'])->name('user.items.undo');
});