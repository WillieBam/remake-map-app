<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});

//news routes

Route::controller(newsController::class)->group(function () {

    //view specific news, after users click the news list
    Route::get('/country/{country_id}/news/{news_id}','viewNews');

    //create news
    Route::get('/dashboard/{user_id}/manage_news/create_news', 'viewCreateNews');
    Route::post('/dashboard/{user_id}/manage_news/create_news', 'createNews');

    //View news list (with optional selected news)
    Route::get('/dashboard/{user_id}/manage_news','viewAllNews');
    Route::get('/dashboard/{user_id}/manage_news/{news_id}', 'viewAllNews');
    // Search news (POST)
    Route::post('/dashboard/{user_id}/manage_news/search', 'searchNews');

    //edit news
    Route::get('/dashboard/{user_id}/manage_news/edit_news/{news_id}', 'viewEditNews');
    Route::post('/dashboard/{user_id}/manage_news/edit_news/{news_id}', 'editNews');

    //delete news
    Route::delete('/dashboard/{user_id}/manage_news/delete_news/{news_id}', 'deleteNews');

});