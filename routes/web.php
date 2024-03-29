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
     return redirect()->away('https://www.unifast.gov.ph');
});

//list heis
// Route::get('/listhei', [HeiController::class, 'getHeiTypes']);
Route::get('/listhei', [HeiController::class, 'getRegions']);
Route::get('/listhei/{region}', [HeiController::class, 'getProvinces']);
Route::get('/listhei/{region}/{province}', [HeiController::class, 'getHeis']);
Route::get('/listhei/{region}/{province}/{heitype}', [HeiController::class, 'getHeis']);

Route::get('/disinfoTES/{region?}/{province?}/{hei?}', [HeiController::class, 'disbursementInfoTES']);
Route::get('/disinfoTDP/{region?}/{province?}/{hei?}', [HeiController::class, 'disbursementInfoTDP']);

Route::get('/searchhei/{hei}', [HeiController::class, 'searchhei']);
Route::get('/hei/{hei}', [HeiController::class, 'getHeiInfo']);
Route::get('/courses/{heiuii}', [HeiController::class, 'getCourses']);
