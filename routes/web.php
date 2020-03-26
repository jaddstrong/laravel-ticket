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

Route::get('users', ['uses'=>'UsersController@dataTables', 'as'=>'users.index']);

// ADMIN ROUTES
Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){

    Route::get('/adminDataTables', ['uses'=>'AdminsController@dataTables', 'as'=>'admin.dataTables']);
    Route::get('/admin', 'AdminsController@index');
    Route::get('/admin/{id}/show','AdminsController@show');
    Route::get('/admin/{id}/add','AdminsController@add');
    Route::post('/admin/{id}/logs','AdminsController@logs');
    Route::get('/admin/pending', 'AdminsController@pending');
    Route::post('/admin/comment', 'AdminsController@comment');
    Route::post('/admin/{id}/return', 'AdminsController@return');
    Route::post('/admin/{id}/solve', 'AdminsController@solve');
    Route::post('/admin/{id}/open', 'AdminsController@open');
    Route::get('/admin/archive', 'AdminsController@archive');

});


// USER ROUTES
Route::group(['middleware' => 'App\Http\Middleware\UserMiddleware'], function(){
    
    Route::get('/userDataTables', ['uses'=>'UsersController@dataTables', 'as'=>'user.dataTables']);
    Route::get('/user', 'UsersController@index');
    Route::post('/user', 'UsersController@store');
    Route::get('/user/{id}', 'UsersController@show');
    Route::get('/user/{id}/edit', 'UsersController@edit');
    Route::post('/user/{id}/update', 'UsersController@update');
    Route::delete('/user/{id}/delete', 'UsersController@destroy');
    Route::post('/user/{id}/comment', 'UsersController@comment');
    Route::get('/archive', 'UsersController@archive');
    Route::post('/user/solve', 'UsersController@solve');
    Route::post('/user/reopen', 'UsersController@reopen');
    Route::get('/userArchive', 'UsersController@userArchive');

});

Auth::routes();




