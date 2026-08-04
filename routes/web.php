<?php

use App\Http\Controllers\Admin\AllocationController as AdminAllocationController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HostelController as AdminHostelController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OccupancyController as AdminOccupancyController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HostelPublicController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Student\AllocationController as StudentAllocationController;
use App\Http\Controllers\Student\ApplicationController as StudentApplicationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\RulesController as StudentRulesController;
use App\Http\Controllers\Student\SettingsController as StudentSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public marketing site
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/facilities', [PageController::class, 'facilities'])->name('facilities');
Route::get('/hostels', [HostelPublicController::class, 'index'])->name('hostels');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Student authentication (default "web" guard)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});
Route::post('/logout', [StudentAuthController::class, 'logout'])->middleware('auth:web')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin authentication ("admin" guard)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.attempt');

        Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    });
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Student area (auth:web)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('auth:web')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [StudentProfileController::class, 'updatePhoto'])->name('profile.photo');

    Route::get('/application', [StudentApplicationController::class, 'create'])->name('application');
    Route::post('/application', [StudentApplicationController::class, 'store'])->name('application.store');

    Route::get('/allocation', [StudentAllocationController::class, 'show'])->name('allocation');

    Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [StudentNotificationController::class, 'markAllRead'])->name('notifications.read');

    Route::get('/rules', [StudentRulesController::class, 'index'])->name('rules');

    Route::get('/settings', [StudentSettingsController::class, 'edit'])->name('settings');
    Route::put('/settings', [StudentSettingsController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Admin area (auth:admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/students', [AdminStudentController::class, 'index'])->name('students');
    Route::post('/students', [AdminStudentController::class, 'store'])->name('students.store');
    Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/hostels', [AdminHostelController::class, 'index'])->name('hostels');
    Route::post('/hostels', [AdminHostelController::class, 'store'])->name('hostels.store');
    Route::put('/hostels/{hostel}', [AdminHostelController::class, 'update'])->name('hostels.update');
    Route::delete('/hostels/{hostel}', [AdminHostelController::class, 'destroy'])->name('hostels.destroy');

    Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms');
    Route::post('/rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications');
    Route::post('/applications/{application}/approve', [AdminApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [AdminApplicationController::class, 'reject'])->name('applications.reject');

    Route::get('/allocation', [AdminAllocationController::class, 'index'])->name('allocation');
    Route::get('/allocation/available-rooms', [AdminAllocationController::class, 'availableRooms'])->name('allocation.rooms');
    Route::post('/allocation', [AdminAllocationController::class, 'store'])->name('allocation.store');
    Route::post('/allocation/{allocation}/vacate', [AdminAllocationController::class, 'vacate'])->name('allocation.vacate');

    Route::get('/occupancy', [AdminOccupancyController::class, 'index'])->name('occupancy');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read');

    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages');
    Route::post('/messages/{message}/mark-read', [AdminMessageController::class, 'markRead'])->name('messages.read');
    Route::post('/messages/{message}/mark-unread', [AdminMessageController::class, 'markUnread'])->name('messages.unread');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('settings');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [AdminProfileController::class, 'updatePhoto'])->name('profile.photo');
});
