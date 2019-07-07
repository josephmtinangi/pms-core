<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Http\Controllers\Controller;

class PropertyFloorController extends Controller
{
    public function show($propertyId, $floor)
    {
    	$property = Property::find($propertyId);

    	if(!$property)
    	{
	        return response([
	            'status' => 400,
	            'statusText' => 'Bad request',
	            'message' => 'Not found',
	            'ok' => false,
	            'data' => $property,
	        ], 400);     		
    	}

    	$contracts = CustomerContract::with('customer')->with(['rooms.room' => function ($query) use ($floor) {
    		$query->whereFloor($floor);
    	}])->wherePropertyId($property->id)->get();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => [
            	'number' => $floor,
            	'contracts' => $contracts,
            ],
        ], 200);    	
    }
}
