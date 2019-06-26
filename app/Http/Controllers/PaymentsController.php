<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CustomerPaymentSchedule;

class PaymentsController extends Controller
{
    public function inquire(Request $request)
    {
    	// Validate token

    	// Validate checksum

    	// Decode control number

    	$controlNumber = $request->paymentReference;
    	$initial = substr($controlNumber, 0, 2);
    	$accountCode = substr($controlNumber, 2,3);
    	$chargeableTypeCode = substr($controlNumber, 3,1);
    	$chargeableCode = substr($controlNumber, 6,3);
    	$chargeableRandom = substr($controlNumber, 9,14);

    	$payerName = null;
    	$amount = null;
    	$paymentReference = null;
    	$paymentType = null;
    	$paymentDesc = null;
    	if($chargeableTypeCode == 0)
    	{
    		$customerPaymentSchedule = CustomerPaymentSchedule::whereControlNumber($controlNumber)->first();
    		if(!$customerPaymentSchedule)
    		{
		        return response([
		            'status' => 201,
		            'statusDesc' => 'Invalid Token',
		            'data' => null,
		        ], 400);    			
    		}
    		// Valid
    		if($customerPaymentSchedule->expiry_date > Carbon::today())
    		{
		        return response([
		            'status' => 205,
		            'statusDesc' => 'Payment reference number has expired',
		            'data' => null,
		        ], 400);
    		}
    		$payerName = $customerPaymentSchedule->customerContract->customer->name();
    		$amount = $customerPaymentSchedule->amount_to_be_paid;
    		$paymentReference = $customerPaymentSchedule->control_number;
    		$paymentType = $accountCode;
    		$paymentDesc = 'Rent from '.$customerPaymentSchedule->customerContract->start_date.' to '.$customerPaymentSchedule->customerContract->start_date. ' for room(s) ';
    		foreach($customerPaymentSchedule->customerContract->rooms as $room)
    		{
    			$paymentDesc .= $room->room->number.', ';
    		}
    	}
    	if($chargeableTypeCode == 1)
    	{
    		// Client
    	}

        return response([
            'status' => 200,
            'statusDesc' => 'success',
            'data' => [
            	'payerName' => $payerName,
            	'amount' => $amount,
            	'amountType' => 'FIXED',
            	'currency' => 'TZS',
            	'paymentReference' => $paymentReference,
            	'paymentType' => $paymentType,
            	'paymentDesc' => $paymentDesc,
            ],
        ], 200);    	
    }

    public function pay(Request $request)
    {
        return response([
            'status' => 200,
            'statusDesc' => 'success',
            'data' => [
            	'payerName' => null,
            ],
        ], 200);    	
    }
}
