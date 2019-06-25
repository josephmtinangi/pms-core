<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\RealEstateAgent;
use App\Models\CustomerContract;
use App\Http\Controllers\Controller;
use App\Models\CustomerPaymentSchedule;

class InvoicesController extends Controller
{
    public function index()
    {
    	$invoices = Invoice::latest()->paginate(100);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $invoices,
        ], 200);
    }

    public function show($id)
    {
    	$invoice = Invoice::find($id);

    	$agent = RealEstateAgent::first();

    	$customerPaymentSchedule = CustomerPaymentSchedule::find($invoice->invoiceable_id);

    	$customerContract = CustomerContract::with(['customer', 'property', 'rooms', 'customer'])
    											->find($customerPaymentSchedule->customer_contract_id);

    	if(!$invoice)
    	{
	        return response([
	            'status' => 400,
	            'statusText' => 'error',
	            'message' => 'Not found',
	            'ok' => true,
	            'data' => $invoice,
	        ], 400);
    	}

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => [
            	'invoice' => $invoice,
            	'agent' => $agent,
            	'customerPaymentSchedule' => $customerPaymentSchedule,
            	'customerContract' => $customerContract,
            ],
        ], 200);
    }
}
