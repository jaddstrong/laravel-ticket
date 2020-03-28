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

Auth::routes();
Route::get('/home', 'HomeController@index');
Route::get('/', function () {
    return view('welcome');
});

// ADMIN ROUTES
Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){

    Route::get('/admin', 'AdminsController@index');
    Route::get('/admin/archive', 'AdminsController@archive');
    Route::get('/admin/pending', 'AdminsController@pending');
    //TICKET ROUTES
    Route::post('/admin/{id}/return', 'TicketsController@return');
    Route::get('/admin/{id}/add','TicketsController@add');

});

// USER ROUTES
Route::group(['middleware' => 'App\Http\Middleware\UserMiddleware'], function(){

    Route::get('/user', 'UsersController@index');
    Route::get('/archive', 'UsersController@archive');
    Route::get('/userArchive', 'UsersController@userArchive');
    //TICKET ROUTES
    Route::post('/user', 'TicketsController@store');
    Route::get('/user/{id}/edit', 'TicketsController@edit');
    Route::post('/user/{id}/update', 'TicketsController@update');
    Route::delete('/user/{id}/delete', 'TicketsController@destroy');

});

//ALL USER`S
Route::group(['middleware' => 'App\Http\Middleware\AuthCheck'], function(){

    //TICKETS
    Route::get('/dataTables', ['uses'=>'TicketsController@dataTables', 'as'=>'dataTables']);
    Route::get('/ticket/{id}', 'TicketsController@show');
    Route::post('/ticket/solve', 'TicketsController@solve');
    Route::post('/ticket/reopen', 'TicketsController@reopen');
    //COMMENTS
    Route::post('/comment', 'CommentsController@comment');
    //LOGS
    Route::post('/logs','LogsController@logs');

});





