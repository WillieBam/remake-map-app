<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MessagesController;


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

//Route::view('/','welcome');
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
Route::get('/countries/search', [CountryController::class, 'searchCountry'])->name('countries.search');
Route::get('/countries/{id}', [CountryController::class, 'index'])->name('countries.show');

//Route::get('/countries/{id}/message/create', [MessagesController::class, 'create'])->name('message.create');
Route::post('/countries/{id}/create-message', [MessagesController::class, 'store'])->name('message.add');








