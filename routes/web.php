<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HeiController;
use Illuminate\Support\Facades\Artisan;

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

// Route::get('/sample/{id}', [HeiController::class, 'show']);
Route::get('/listhei/{heitype}/{region}', [HeiController::class, 'showhei']);
Route::get('/searchhei/{hei}', [HeiController::class, 'searchhei']);
Route::get('/hei/{hei}', [HeiController::class, 'getHeiInfo']);
Route::get('/courses/{heiuii}', [HeiController::class, 'getCourses']);
