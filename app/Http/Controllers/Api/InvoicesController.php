<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\RealEstateAgent;
use App\Models\CustomerContract;
use App\Http\Controllers\Controller;
use App\Models\ClientPaymentSchedule;
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

    	$agent = RealEstateAgent::latest()->first();

        if($invoice->invoiceable_type == get_class(new ClientPaymentSchedule))
        {
            $clientPaymentSchedule = ClientPaymentSchedule::with('client')->find($invoice->invoiceable_id);
            return response([
                'status' => 200,
                'statusText' => 'success',
                'message' => '',
                'ok' => true,
                'data' => [
                    'invoice' => $invoice,
                    'agent' => $agent,
                    'schedule' => $clientPaymentSchedule,
                    'contract' => null,
                    'invoiceable_type' => $invoice->invoiceable_type,
                ],
            ], 200);
        }
        if($invoice->invoiceable_type == get_class(new CustomerPaymentSchedule)){ 
        	$customerPaymentSchedule = CustomerPaymentSchedule::with('customerContract.customer')->find($invoice->invoiceable_id);
        	$customerContract = CustomerContract::with(['customer', 'property', 'rooms', 'rooms.room', 'customer'])
    											->find($customerPaymentSchedule->customer_contract_id);
            return response([
                'status' => 200,
                'statusText' => 'success',
                'message' => '',
                'ok' => true,
                'data' => [
                    'invoice' => $invoice,
                    'agent' => $agent,
                    'schedule' => $customerPaymentSchedule,
                    'contract' => $customerContract,
                    'invoiceable_type' => $invoice->invoiceable_type,
                ],
            ], 200);
        }
    }
}
