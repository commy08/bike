<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::resource('/form', 'FormController');
    // Route::resource('/amphur', \API\AmphurController::class);
    Route::resource('/division', \API\DivisionController::class);
    Route::resource('/event', \API\EventController::class);
    Route::resource('/invoice', \API\InvoiceController::class);
    Route::resource('/pic', \API\PicController::class);
    Route::get('/select', \API\EventController::class.'@select');
    Route::get('/address', \API\AddressController::class.'@index');

    // Route::get('/division', \API\DivisionController::class.'@methods');

    Route::get('/callback', \API\UsersController::class.'@callback');
    Route::get('/login', \API\UsersController::class.'@index');

    Route::post('/getUser', \API\UsersController::class.'@showUser');
    Route::post('/registerOrg',\API\UsersController::class.'@registerOrg');
    Route::post('/registerUser',\API\UsersController::class.'@registerUser');