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
   
   Route::resource('regions', 'Api\RegionsController');
   Route::resource('districts', 'Api\DistrictsController');
   Route::resource('wards', 'Api\WardsController');
   Route::resource('villages', 'Api\VillagesController');
   
   Route::resource('client-types', 'Api\ClientTypesController');
   Route::resource('clients', 'Api\ClientsController');
   
   Route::resource('property-types', 'Api\PropertyTypesController');
   Route::resource('properties', 'Api\PropertiesController');
   
   Route::resource('customer-types', 'Api\CustomerTypesController');
   Route::resource('customers', 'Api\CustomersController');
});
