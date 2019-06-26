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

use App\Models\Invoice;
use App\Models\RealEstateAgent;
use App\Models\CustomerContract;
use App\Models\CustomerPaymentSchedule;
Route::get('test/{invoice}', function (Invoice $invoice) {

	$agent = RealEstateAgent::latest()->first();
	$customerPaymentSchedule = CustomerPaymentSchedule::find($invoice->invoiceable_id);
	$customerContract = CustomerContract::with(['customer', 'property', 'rooms', 'rooms.room', 'customer'])
											->find($customerPaymentSchedule->customer_contract_id);	

	return view('invoice', compact(['agent','invoice', 'customerPaymentSchedule','customerContract']));
});

Route::get('invoice/{invoice}', 'PdfController@show');

Route::get('invoice/{invoice}/save', 'PdfController@save');

Route::get('attachments/{category}/{filename}', 'Api\AttachmentsController@index');
