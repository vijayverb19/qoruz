<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/users', function()
{
    return 'Users!';
});


Route::get('/TasksView', 	'App\Http\Controllers\taskController@getAllTask');

Route::any('/AddTask', 		'App\Http\Controllers\taskController@insertTask');

Route::any('/DeleteTask', 	'App\Http\Controllers\taskController@deleteTask');

Route::any('/UpdateTask', 	'App\Http\Controllers\taskController@updateTask');

Route::any('/AddSubTask', 		'App\Http\Controllers\taskController@insertSubTask');

Route::any('/DeleteOld', 		'App\Http\Controllers\taskController@deleteOldTask');

