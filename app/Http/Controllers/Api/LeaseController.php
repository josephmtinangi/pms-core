<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Models\CustomerContractRoom;
use App\Http\Controllers\Controller;
use App\Models\CustomerPaymentSchedule;

class LeaseController extends Controller
{
    public function store(Request $request)
    {
    	// which customer
    	$customer = Customer::find($request->customer_id);
		if(!$customer)
		{
	        return response([
	            'status' => 400,
	            'statusText' => 'error',
	            'message' => 'Customer not found',
	            'ok' => true,
	            'data' => null,
	        ], 400);			
		}    	
    	// which property
    	$property = Property::find($request->property_id);
		if(!$property)
		{
	        return response([
	            'status' => 400,
	            'statusText' => 'error',
	            'message' => 'Property not found',
	            'ok' => true,
	            'data' => null,
	        ], 400);			
		}  	
    	// which rooms

    	$customerContract = new CustomerContract();
    	$customerContract->customer_id = $customer->id;
    	$customerContract->property_id = $property->id;
    	$customerContract->start_date = Carbon::today()->addMonths(-5);
    	$customerContract->end_date = Carbon::today()->addMonths(7);
    	$customerContract->control_number = null;
    	$customerContract->rent_per_month = $request->rent_per_month;
    	$customerContract->payment_interval = $request->payment_interval;
    	$customerContract->contract_duration = $request->contract_duration;
    	$customerContract->save();

    	$roomIds = explode(',', $request->rooms);
    	foreach ($roomIds as $key => $value) {
    		$room = Room::find($value);

    		if($room){
	    		$customerContractRoom = new CustomerContractRoom;
	    		$customerContractRoom->customer_contract_id = $customerContract->id;
	    		$customerContractRoom->room_id = $room->id;
	    		$customerContractRoom->save();
	    	}
    	}

    	$start_date = $customerContract->start_date;
    	return $end_date = $start_date->addMonths($customerContract->payment_interval);
        $start_date = $customerContract->start_date->addMonths(0 - $customerContract->payment_interval);
    	
    	$incrementor = 1;
    	$amount = $customerContract->rent_per_month * $customerContract->payment_interval;
        
    	do {
            \Log::info('start_date = ' . $start_date);
    		\Log::info('end_date = ' . $end_date);

    		$customerPaymentSchedule = new CustomerPaymentSchedule;
    		$customerPaymentSchedule->customer_contract_id = $customerContract->id;
    		$customerPaymentSchedule->start_date = $start_date;
    		$customerPaymentSchedule->end_date = $start_date->addMonths($customerContract->payment_interval);
    		$customerPaymentSchedule->amount_to_be_paid = $amount * $incrementor;
    		$customerPaymentSchedule->amount_remained = $amount * $incrementor;

    		if($customerPaymentSchedule->end_date < Carbon::today())
    		{
    			$customerPaymentSchedule->active = false;
    		}
    		else
    		{
    			$customerPaymentSchedule->active = true;
    		}
	    	$customerPaymentSchedule->save();

	    	$incrementor++;
	    	$start_date = $customerPaymentSchedule->end_date;
	    	$end_date = $customerPaymentSchedule->end_date;
    	} while($end_date < Carbon::today());

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $customer,
        ], 200);    	
    }
}
