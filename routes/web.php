<?php

use Illuminate\Support\Facades\Route;
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
Route::view('/viewSignUp','viewSignUp');
Route::post('/viewSignUp',[UserController::class,'createUser']);
Route::get('/deleteUser/{userId}',[UserController::class,'deleteUser']);
Route::get('/deleteAdmin/{userId}',[UserController::class,'deleteAdmin']);
Route::get('/profile/{userId}',[UserController::class,'viewUser']);
Route::post('/profile/{userId}',[UserController::class,'updateUser']);
Route::get('/viewAllUser',[UserController::class,'adminViewUser']);
Route::get('/viewAllAdmin',[UserController::class,'globalAdminViewAdmin']);
Route::get('/banUser/{userId}',[UserController::class,'banUser']);
Route::get('/banAdmin/{userId}',[UserController::class,'banAdmin']);
Route::get('/changePassword/{userId}',[UserController::class,'viewChangePassword']);
Route::post('/changePassword/{userId}',[UserController::class,'changePassword']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
