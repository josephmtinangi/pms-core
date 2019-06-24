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
    	
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

    	$customerContract = new CustomerContract();
    	$customerContract->customer_id = $customer->id;
    	$customerContract->property_id = $property->id;
    	$customerContract->start_date = $start_date;
    	$customerContract->end_date = $end_date;

    	$customerContract->control_number = null;

    	$customerContract->rent_per_month = $request->rent_per_month;
    	$customerContract->payment_interval = $request->payment_interval;
    	$customerContract->contract_duration = $request->contract_duration;
    	$customerContract->save();

    	$roomIds = explode(',', $request->rooms);
    	foreach ($roomIds as $key => $value) {
    		$room = Room::wherePropertyId($property->id)->find($value);

    		if($room){
                if($room->status == 'active'){
                    $room->status = 'rented';
                    $room->save();

                    $customerContractRoom = new CustomerContractRoom;
                    $customerContractRoom->customer_contract_id = $customerContract->id;
                    $customerContractRoom->room_id = $room->id;
                    $customerContractRoom->save();                
                }
	    	}
    	}

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $customerContract,
        ], 200);    	
    }
}
