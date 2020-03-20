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

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/admin', 'AdminsController@index');
// Route::get('/user', 'UsersController@index');

// Route::resource('/user','UsersController');
Route::get('/user', 'UsersController@index');
Route::post('/user', 'UsersController@store');
Route::get('/user/{id}', 'UsersController@show');
Route::get('/user/{id}/edit', 'UsersController@edit');
Route::post('/user/{id}', 'UsersController@update');
Route::delete('/user/{id}', 'UsersController@destroy');


Route::resource('/admin/pending','PendingController');

