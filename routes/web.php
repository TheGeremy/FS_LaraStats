<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

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

Route::get('/', [FrontController::class, 'home']);

/*
Route::get('/', function () {
    return view('index', [ 'active_mi' => 'home_overview']);
});
*/

Route::get('/stats', function () {
    return view('stats', [ 'active_mi' => 'stats']);
});

Route::get('/missions', function () {
    return view('missions', [ 'active_mi' => 'missions']);
});

Route::get('/test', function () {
	return view('test')->with([
		'active_mi' => 'test',
		'name' => 'Taylor' 
	]);
});


Route::view('/about', 'about', ['active_mi' => 'about']);
Route::view('/farms', 'farms', ['active_mi' => 'farms']);
Route::view('/fields', 'fields', ['active_mi' => 'fields']);
Route::view('/forestry', 'forestry', ['active_mi' => 'forestry']);
Route::view('/settings', 'settings', ['active_mi' => 'settings']);

/*
Route::get('/', function () {
    return view('welcome');
});
*/
