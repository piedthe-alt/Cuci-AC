<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AcModelController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Protected Routes for Regular Users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    // Staff Assignment Routes (MUST be before resource routes to avoid matching {order})
    Route::middleware('is-admin')->group(function () {
        Route::get('/orders/assignments', [OrderController::class, 'staffAssignments'])->name('orders.assignments');
        Route::get('/orders/{order}/assign', [OrderController::class, 'showAssignForm'])->name('orders.assign-form');
        Route::post('/orders/{order}/assign', [OrderController::class, 'assignStaff'])->name('orders.assign-staff');
    });

    // Order Routes
    Route::resource('orders', OrderController::class);

    // Staff Dashboard
    Route::get('/staff-dashboard', [OrderController::class, 'staffDashboard'])
        ->middleware('is-staff')
        ->name('staff.dashboard');

    // Update Order Status (for staff)
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->middleware('is-staff')
        ->name('orders.update-status');
});

// Admin Routes
Route::middleware(['auth', 'is-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/work-management', [AdminController::class, 'workManagement'])->name('work-management');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'listUsers'])->name('index');
        Route::get('/{id}', [AdminController::class, 'showUser'])->name('show');
        Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateUser'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('delete');
        Route::post('/{id}/role', [AdminController::class, 'changeUserRole'])->name('change-role');
        Route::post('/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('toggle-status');
    });

    // AC Model Management
    Route::resource('ac-models', AcModelController::class);

    // Service Management (Parent/Induk)
    Route::resource('services', ServiceController::class);

    // Service Type Management (Child/Anak)
    Route::resource('service-types', ServiceTypeController::class);
});
