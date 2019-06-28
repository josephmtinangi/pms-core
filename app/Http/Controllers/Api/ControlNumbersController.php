<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Http\Controllers\Controller;
use App\Models\CustomerPaymentSchedule;

class ControlNumbersController extends Controller
{
    public function index()
    {

    	$customerPaymentSchedule = CustomerPaymentSchedule::with('invoices')->latest()->paginate(100);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $customerPaymentSchedule,
        ], 200);    	
    }

    public function store(Request $request)
    {
    	$lease = CustomerContract::find($request->customer_contract_id);

    	$existingCustomerPaymentSchedule = CustomerPaymentSchedule::whereCustomerContractId($lease->id)->whereActive(true)->first();

    	$customerPaymentSchedule = new CustomerPaymentSchedule;
    	$customerPaymentSchedule->customer_contract_id = $lease->id;
    	if(!$existingCustomerPaymentSchedule)
    	{
    		$customerPaymentSchedule->start_date = $lease->start_date;
    		$customerPaymentSchedule->end_date = $lease->start_date->addMonths($lease->payment_interval);
    		$customerPaymentSchedule->expiry_date = $lease->start_date->addMonths($lease->payment_interval);
    		$customerPaymentSchedule->amount_to_be_paid = $lease->rent_per_month * $lease->payment_interval;
    		$customerPaymentSchedule->active = true;
    	}
    	else
    	{
    		$customerPaymentSchedule->start_date = $existingCustomerPaymentSchedule->end_date;
    		$customerPaymentSchedule->end_date = $existingCustomerPaymentSchedule->end_date->addMonths($lease->payment_interval);
    		$customerPaymentSchedule->expiry_date = $existingCustomerPaymentSchedule->end_date->addMonths($lease->payment_interval);
    		$customerPaymentSchedule->amount_to_be_paid = $lease->rent_per_month * $lease->payment_interval;
    		$customerPaymentSchedule->active = true;

    		$existingCustomerPaymentSchedule->active = false;
    		$existingCustomerPaymentSchedule->save();
    	}

    	$initial = config()->get('pms.control_number.initial');
    	$accountCode = $lease->property->client->accounts->first()->code;
    	$chargeableCode = 0;
    	$customerCode = $lease->customer->code;
    	$customerRandom = sprintf('%06d', rand(1, 99999));

    	$control_number = $initial.''.$accountCode.''.$chargeableCode.''.$customerCode.''.$customerRandom;

		$customerPaymentSchedule->control_number = $control_number;

    	$customerPaymentSchedule->save();

        // Generate invoice
        $invoice = new Invoice;

        if(!Invoice::latest()->first())
        {
            $invoice->number = sprintf('%06d', 1);
        }
        else
        {
            $invoice->number = sprintf('%06d', ((int)Invoice::latest()->first()->number) + 1);
        }        

        $customerPaymentSchedule->invoices()->save($invoice);        

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => [
                'customerPaymentSchedule' => $customerPaymentSchedule,
                'invoice' => $customerPaymentSchedule->invoices()->first(),
            ],
        ], 200);    	
    }
}
