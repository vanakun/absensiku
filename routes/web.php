<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route for accessing the wedding invitation page without login

// Default route
Route::view('/', 'welcome');

// Dashboard routes for admin
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/admin/home', [HomeController::class, 'index'])->name('admin.home');
    Route::get('/admin/settings/kantor', [AdminController::class, 'settingsKantor'])->name('kantor');
    Route::put('/admin/settings/kantor', [AdminController::class, 'updateAllKantor'])->name('update-all-kantor');

    Route::get('/admin/aproval-cuti', [AdminController::class, 'indexcuti'])->name('indexcuti');
    Route::put('/cuti/approve-cuti/{id}', [AdminController::class, 'approve'])->name('cuti.approve');
    Route::put('/admin/cuti/reject/{id}', [AdminController::class, 'reject'])->name('cuti.reject');

    Route::get('/admin/aproval-izin', [AdminController::class, 'indexizin'])->name('indexizin');
    Route::put('/cuti/approve-izin/{id}', [AdminController::class, 'approveizin'])->name('cuti.approveizin');
    Route::put('/admin/izin/reject/{id}', [AdminController::class, 'rejectizin'])->name('cuti.rejectizin');
  
    Route::get('/generate-absensi-report', [AdminController::class, 'generateAbsensiReportForm'])->name('absensi.report.form');
    Route::post('/generate-absensi-report', [AdminController::class, 'generateAbsensiReport'])->name('absensi.report.generate');
    Route::get('/export-attendance', [AbsensiController::class, 'exportAttendance'])->name('export.attendance');

    // Links that return views, to get components from there
    Route::view('/buttons', 'admin.buttons')->name('buttons');
    Route::view('/cards', 'admin.cards')->name('cards');
    Route::view('/charts', 'admin.charts')->name('charts');
    Route::view('/forms', 'admin.forms')->name('forms');
    Route::view('/modals', 'admin.modals')->name('modals');
    Route::view('/tables', 'admin.tables')->name('tables');

    Route::group(['prefix' => 'pages', 'as' => 'page.'], function () {
        // Route for managing undangan resource
     
        
        // Other routes
        Route::view('/404-page', 'admin.pages.404')->name('404');
        Route::view('/blank-page', 'admin.pages.blank')->name('blank');
        Route::view('/create-account-page', 'admin.pages.create-account')->name('create-account');
        Route::view('/forgot-password-page', 'admin.pages.forgot-password')->name('forgot-password');
        Route::view('/login-page', 'admin.pages.login')->name('login');
    });
});

// User routes
Route::group(['middleware' => ['auth','user'], 'prefix' => 'dashboard', 'as' => 'user.'], function () {
    Route::get('/user/home', [UserController::class, 'index'])
        ->middleware(['auth', 'user'])
        ->name('user.home');
    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('/absensi/print/{year}/{month}', [UserController::class, 'printPDF'])->name('printPDF');
    Route::get('/my-timestamp', [UserController::class, 'timestamp'])->name('timestamp');

    Route::get('/my-leave', [UserController::class, 'leave'])->name('leave');
    Route::get('/create-leave', [UserController::class, 'Createleave'])->name('Createleave');
    Route::post('/leave/store', [UserController::class, 'StoreCuti'])->name('StoreCuti');

    Route::get('/izin', [UserController::class, 'izin'])->name('izin');
    Route::get('/create-izin', [UserController::class, 'Createizin'])->name('Createizin');
    Route::post('/izin/store', [UserController::class, 'Storeizin'])->name('Storeizin');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/submit-absen', [AbsensiController::class, 'submitAbsen'])->name('submit.absen');
});

// Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Authentication routes
require __DIR__ . '/auth.php';
