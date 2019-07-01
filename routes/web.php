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

use App\Models\CustomerContract;
use App\Utilities\Helper;
use App\Models\PaymentReference;
Route::get('/{id}', function ($id) {

	$unique = false;
	do {

		$lease = CustomerContract::find($id);
		$controlNumber = Helper::generateCustomerControlNumber($lease);

		try {

			$paymentReference = new PaymentReference;
			$paymentReference->number = $controlNumber;
			$paymentReference->save();

			$unique = true;
		}catch(Exception $e){
			$unique = false;
		}
	}while(!$unique);

    return $paymentReference;
});

Route::get('invoice/{invoice}', 'PdfController@show');

Route::get('attachments/{category}/{filename}', 'Api\AttachmentsController@index');

