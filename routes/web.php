<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

// Report routes
Route::get('/dashboard/reports/{message_id}', [ReportController::class, 'viewReportsWithId'])->name('viewReportsWithId');
Route::get('/dashboard/reports', [ReportController::class, 'viewReports'])->name('viewReports');
Route::post('/dashboard/reports/search', [ReportController::class, 'searchReports'])->name('searchReports');