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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', 'AuthController@register');

Route::post('auth/login', 'AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');  
});

Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});

Route::group(['middleware' => 'jwt.auth'], function(){
   
   Route::post('auth/logout', 'AuthController@logout');
   
   Route::get('dashboard', 'Api\DashboardController@index');
   
   Route::resource('users', 'Api\UsersController');
   
   Route::resource('payment-modes', 'Api\PaymentModesController');
   
   Route::resource('regions', 'Api\RegionsController');
   Route::get('regions/{region}/districts', 'Api\RegionsController@districts');
   Route::resource('districts', 'Api\DistrictsController');
   Route::get('districts/{district}/wards', 'Api\DistrictsController@wards');
   Route::resource('wards', 'Api\WardsController');
   Route::get('wards/{ward}/villages', 'Api\WardsController@villages');
   Route::resource('villages', 'Api\VillagesController');
   
   Route::resource('client-types', 'Api\ClientTypesController');
   Route::get('clients/all', 'Api\ClientsController@all');
   Route::resource('clients', 'Api\ClientsController');
   
   Route::resource('property-types', 'Api\PropertyTypesController');
   Route::get('properties/all', 'Api\PropertiesController@all');
   Route::get('properties/{property}/rooms', 'Api\PropertiesController@rooms');
   Route::resource('properties', 'Api\PropertiesController');
   
   Route::resource('customer-types', 'Api\CustomerTypesController');
   Route::get('customers/all', 'Api\CustomersController@all');
   Route::resource('customers', 'Api\CustomersController');
   
   Route::resource('accounts', 'Api\AccountsController');
   
   Route::post('rooms/upload', 'Api\RoomsController@upload');
   Route::resource('rooms', 'Api\RoomsController');

   Route::resource('leases', 'Api\LeaseController');
   
   Route::resource('control-numbers', 'Api\ControlNumbersController');
   
   Route::resource('invoices', 'Api\InvoicesController');

   Route::post('invoice/{invoice}/save', 'PdfController@save');
   
   Route::get('client-payments', 'Api\ClientPaymentController@index');
   Route::get('customer-payments', 'Api\CustomerPaymentController@index');

   Route::resource('real-estate-agents', 'Api\RealEstateAgentsController');
});

Route::post('payments/inquire', 'PaymentsController@inquire');
Route::post('payments/pay', 'PaymentsController@pay');
