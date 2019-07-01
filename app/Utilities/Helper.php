<?php

namespace App\Utilities;

use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;
use App\Models\CustomerContract;
class Helper
{
	public static function createResponse($status, $statusText, $message, $ok, $data)
	{
        return response([
            'status' => $status,
            'statusText' => $statusText,
            'message' => $message,
            'ok' => $ok,
            'data' => $data,
        ], $status);		
	}

 	public static function generateClientControlNumber(Client $client)
	{
        $initial = config()->get('pms.control_number.initial');
        $accountCode = $client->accounts->first()->code;
        $chargeableCode = config()->get('pms.control_number.client');
        $clientCode = $client->code;
        $clientRandom = sprintf('%06d', rand(1, 99999));

        $control_number = $initial.''.$accountCode.''.$chargeableCode.''.$clientCode.''.$clientRandom;

        return $control_number; 		
 	}

 	public static function generateCustomerControlNumber(CustomerContract $lease)
	{
    	$initial = config()->get('pms.control_number.initial');
    	$accountCode = $lease->property->client->accounts->first()->code;
    	$chargeableCode = config()->get('pms.control_number.customer');;
    	$customerCode = $lease->customer->code;
    	$customerRandom = sprintf('%06d', rand(1, 99999));

    	$control_number = $initial.''.$accountCode.''.$chargeableCode.''.$customerCode.''.$customerRandom;

        return $control_number; 		
 	} 	
}
