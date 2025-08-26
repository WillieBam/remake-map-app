<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/deleteUser/{userId}',[UserController::class,'deleteUser']);
Route::get('/deleteAdmin/{userId}',[UserController::class,'deleteAdmin']);
Route::get('/profile/{userId}',[UserController::class,'viewUser']);
Route::post('/profile/{userId}',[UserController::class,'updateUser']);
Route::get('/viewAllUser',[UserController::class,'adminViewUser']);
Route::get('/viewAllAdmin',[UserController::class,'globalAdminViewAdmin']);
Route::get('/banUser/{userId}',[UserController::class,'banUser']);
Route::get('/banAdmin/{userId}',[UserController::class,'banAdmin']);
Route::get('/', function () {
    return view('welcome');
});

// Report routes
Route::get('/dashboard/reports/{message_id}', [ReportController::class, 'viewReportsWithId'])->name('viewReportsWithId');
Route::get('/dashboard/reports', [ReportController::class, 'viewReports'])->name('viewReports');
Route::post('/dashboard/reports/search', [ReportController::class, 'searchReports'])->name('searchReports');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
