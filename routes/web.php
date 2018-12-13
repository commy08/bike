<?php

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

// Route::get('/callback', 'UsersController@callback');
// Route::get('/login', 'UsersController@index');

// Route::post('/getUser', 'UsersController@showUser');
// Route::post('/registerOrg','UsersController@registerOrg');
// Route::post('/registerUser','UsersController@registerUser');




Route::get('/form', 'FormController@index');