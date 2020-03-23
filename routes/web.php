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
Route::get('/home', 'HomeController@index');

// ADMIN ROUTES
Route::get('/admin', 'AdminsController@index');
Route::get('/admin/{id}/show','AdminsController@show');
Route::get('/admin/{id}/add','AdminsController@add');
Route::get('/admin/{id}/logs','AdminsController@logs');
Route::get('/admin/pending', 'AdminsController@pending');
Route::post('/admin/comment', 'AdminsController@comment');
Route::post('/admin/{id}/return', 'AdminsController@return');

// USER ROUTES
Route::get('/user', 'UsersController@index');
Route::post('/user', 'UsersController@store');
Route::get('/user/{id}', 'UsersController@show');
Route::get('/user/{id}/edit', 'UsersController@edit');
Route::post('/user/{id}', 'UsersController@update');
Route::delete('/user/{id}', 'UsersController@destroy');

Auth::routes();




