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

//callback notify
Route::get('/loginnotify', \API\NotifyController::class.'@index');
Route::post('/callbacknotify', \API\NotifyController::class.'@callbackNotify');

// Route user login
Route::get('/user', \API\UsersController::class.'@show');
Route::post('/getUser', \API\UsersController::class.'@showUser');
Route::post('/registerOrg',\API\UsersController::class.'@registerOrg');
Route::post('/registerUser',\API\UsersController::class.'@registerUser');
Route::get('/callback', \API\UsersController::class.'@callback');
Route::get('/login', \API\UsersController::class.'@index');

// Route user
Route::get('/admin/user', \API\UsersController::class.'@checkUser');
Route::post('/admin/updateuser',\API\UsersController::class.'@updateStatusUser');
Route::get('/admin/showuser', \API\UsersController::class.'@showUserRacer');
Route::get('/admin/showorg', \API\UsersController::class.'@showUserOrg');

//Route event
Route::get('/select', \API\EventController::class.'@SelectByProvince');
Route::resource('/event', \API\EventController::class);
Route::post('/admin/updateevent',\API\EventController::class.'@updateStatusEvent');
Route::get('/eventorg', \API\EventController::class.'@showEventOrg');
Route::get('/eventadmin', \API\EventController::class.'@showEventAdmin');
Route::get('/eventtype', \API\EventController::class.'@getEventType');
Route::get('/admin/showevent', \API\EventController::class.'@showEvent');



//Route address
Route::get('/address', \API\AddressController::class.'@index');
Route::get('/getCity', \API\AddressController::class.'@getCity');
Route::get('/getAmp', \API\AddressController::class.'@getAmp'); 

//Route division
Route::resource('/division', \API\DivisionController::class);
//ส่งไปเรียกดู division เพื่อเลือก class การแข่งขัน
Route::get('/getdivision', \API\DivisionController::class.'@getDivision');
Route::post('/newinvoice', \API\DivisionController::class.'@CreateInvoice');


//Route invoice
Route::post('/invoice', \API\InvoiceController::class.'@UpdateInvoice');
Route::post('/invoicepic', \API\InvoiceController::class.'@UpdateInvoicePic');
Route::get('/userhistory', \API\InvoiceController::class.'@history');
Route::get('/orgallinvoice', \API\InvoiceController::class.'@ShowAllInvoiceOrg');
Route::get('/userallinvoice', \API\InvoiceController::class.'@ShowAllInvoiceUser');

//Route pic
Route::resource('/pic', \API\PicController::class);

//Route bank
Route::resource('/bank', \API\BankController::class);

// Route bank
Route::resource('/otp', \API\OtpController::class);

