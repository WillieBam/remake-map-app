<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\newsController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

//news routes

 //view specific news, after users click the news list
    Route::get('/country/{country_id}/news/{news_id}', [newsController::class, 'viewNews']);

Route::controller(newsController::class)->middleware(['auth'])->group(function () {


    //create news
    Route::get('/dashboard/manage_news/create_news', 'viewCreateNews');
    Route::post('/dashboard/manage_news/create_news', 'createNews');

    //View news list (with optional selected news)
    Route::get('/dashboard/manage_news','viewAllNews');
    Route::get('/dashboard/manage_news/{news_id}', 'viewAllNews');
    // Search news (POST)
    Route::post('/dashboard/manage_news/search', 'searchNews');

    //edit news
    Route::get('/dashboard/manage_news/edit_news/{news_id}', 'viewEditNews');
    Route::post('/dashboard/manage_news/edit_news/{news_id}', 'editNews');

    //delete news
    Route::delete('/dashboard/manage_news/delete_news/{news_id}', 'deleteNews');

});