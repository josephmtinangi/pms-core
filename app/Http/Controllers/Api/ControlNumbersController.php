<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Http\Controllers\Controller;
use App\Models\CustomerPaymentSchedule;

class ControlNumbersController extends Controller
{
    public function index()
    {

    	$customerPaymentSchedule = CustomerPaymentSchedule::latest()->paginate(100);

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
    		$customerPaymentSchedule->end_date = $lease->start_date->lastOfMonth();
    		$customerPaymentSchedule->expiry_date = $lease->start_date->lastOfMonth();
    		$customerPaymentSchedule->amount_to_be_paid = $lease->rent_per_month;
    		$customerPaymentSchedule->active = true;
    	}
    	else
    	{
    		$customerPaymentSchedule->start_date = $existingCustomerPaymentSchedule->start_date->addMonth();
    		$customerPaymentSchedule->end_date = $existingCustomerPaymentSchedule->start_date->addMonth()->lastOfMonth();
    		$customerPaymentSchedule->expiry_date = $existingCustomerPaymentSchedule->start_date->addMonth()->lastOfMonth();
    		$customerPaymentSchedule->amount_to_be_paid = $lease->rent_per_month;
    		$customerPaymentSchedule->active = true;

    		$existingCustomerPaymentSchedule->active = false;
    		$existingCustomerPaymentSchedule->save();
    	}

    	$initial = '21';
    	$accountCode = $lease->property->client->accounts->first()->code;
    	$chargeableCode = 0;
    	$customerCode = $lease->customer->code;
    	$customerRandom = rand();

    	$control_number = $initial.''.$accountCode.''.$chargeableCode.''.$customerCode.''.$customerRandom;

		$customerPaymentSchedule->control_number = $control_number;

    	$customerPaymentSchedule->save();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $customerPaymentSchedule,
        ], 200);    	
    }
}
