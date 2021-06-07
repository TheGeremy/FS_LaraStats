<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Models\Savegame;

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

Route::get('/missions/{savegame_id}', function ($savegame_id) {
    return view('missions', [
    	'active_mi' => 'missions',
    	'savegame' => Savegame::with(['missions.status'])->find($savegame_id)
    ]);
});

Route::get('/test', function () {
	return view('test')->with([
		'active_mi' => 'test'
	]);
});

Route::get('/savegames', function () {
	return view('savegames')->with([
		'active_mi' => 'savegames',
		'savegames' => Savegame::all()->sortByDesc('save_date')
	]);
});


Route::view('/about', 'about', ['active_mi' => 'about']);
Route::view('/farms', 'farms', ['active_mi' => 'farms']);
Route::view('/fields', 'fields', ['active_mi' => 'fields']);
Route::view('/forestry', 'forestry', ['active_mi' => 'forestry']);
Route::view('/settings', 'settings', ['active_mi' => 'settings']);


Route::view('/load_savegame', 'load_savegame', ['active_mi' => 'load_savegame']);
Route::view('/load_vehicles', 'load_vehicles', ['active_mi' => 'load_vehicles']);
Route::view('/load_items', 'load_items', ['active_mi' => 'load_items']);

/*
Route::get('/', function () {
    return view('welcome');
});
*/
