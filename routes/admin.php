<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\RepairController;
use App\Http\Controllers\Admin\RepairFeedbackController;
use Illuminate\Support\Facades\Route;



// TODO: Admin Routes : admin.php


Route::middleware(['auth', 'role:Admin'])
    ->group(function () {

        // TODO ADMIN
        Route::controller(DashboardController::class)
            ->prefix('/dashboard')
            ->name('dashboard.')
            ->group(function () {
            Route::get('/', 'AdminDashboard')->name('index');
            Route::get('/statistics', 'statistics')->name('statistics');
        });
        // TODO ROUTE ADMIN CONTROLLER
        Route::controller(AdminController::class)
            ->group(function () {
            Route::get('/logouts', 'AdminLogout')->name('logouts');
            Route::get('/profile', 'AdminProfile')->name('profile');
            Route::post(    '/profile/store', 'AdminProfileStore')->name('profile.store');
            Route::get('/password/change', 'AdminChangePassword')->name('password.change');
            Route::post('/update/password', 'AdminPasswordUpdate')->name('update.password');
        });

        // TODO ROUTE CUSTOMERS CONTROLLER
        Route::controller(CustomerController::class)
            ->prefix('/customers')
            ->name('customers.')
            ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
        });

        // TODO ROUTE REPAIRS CONTROLLER
        Route::controller(RepairController::class)
            ->prefix('/repairs')
            ->name('repairs.')
            ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');

        });

        // TODO ROUTE MAINTENANCE CONTROLLER
        Route::controller(MaintenanceController::class)
            ->prefix('/maintenance')
            ->name('maintenance.')
            ->group(function () {
            Route::get('/reminders', 'reminders')->name('reminders');
            Route::get('/export', 'export')->name('export');
            Route::post('/send-reminder-email/{id}', 'sendReminderEmail')->name('send.reminder.email');
        });

        // TODO ROUTE REPAIR FEEDBACK CONTROLLER
        Route::controller(RepairFeedbackController::class)
            ->prefix('/repair-feedbacks')
            ->name('repair.feedbacks.')
            ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/reply', 'reply')->name('reply');
            Route::get('/export', 'export')->name('export');

        });
    });


Route::get('/admins/login', function () {
    return view('admin.auth.login');
})->name('admins.login');


require __DIR__ . '/auth.php';