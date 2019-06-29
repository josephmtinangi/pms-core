<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\ClientPayment;
use App\Models\CustomSequence;
use App\Models\CustomerPayment;
use App\Models\ClientPaymentSchedule;
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
    	$chargeableTypeCode = substr($controlNumber, 5,1);
    	$chargeableCode = substr($controlNumber, 6,3);
    	$chargeableRandom = substr($controlNumber, 9,14);

    	$payerName = null;
    	$amount = null;
    	$paymentReference = null;
    	$paymentType = null;
    	$paymentDesc = null;
    	$payerId = null;
    	if($chargeableTypeCode == 0)
    	{
    		$customerPaymentSchedule = CustomerPaymentSchedule::whereControlNumber($controlNumber)->first();
    		if(!$customerPaymentSchedule)
    		{
		        return response([
		            'status' => 204,
		            'statusDesc' => 'Invalid payment reference number',
		            'data' => null,
		        ], 400);    			
    		}
    		// Valid
            if($customerPaymentSchedule->paid_at)
            {
                return response([
                    'status' => 203,
                    'statusDesc' => 'Payment reference number already paid',
                    'data' => null,
                ], 400);                
            }
    		if($customerPaymentSchedule->expiry_date < Carbon::today())
    		{
		        return response([
		            'status' => 205,
		            'statusDesc' => 'Payment reference number has expired',
		            'data' => null,
		        ], 400);
    		}
    		$payerName = $customerPaymentSchedule->customerContract->customer->name();
    		$payerId = $customerPaymentSchedule->customerContract->customer->id;
    		$amount = $customerPaymentSchedule->amount_to_be_paid;
    		$paymentReference = $customerPaymentSchedule->control_number;
    		$paymentType = $accountCode;
    		$paymentDesc = 'Rent from '.$customerPaymentSchedule->start_date.' to '.$customerPaymentSchedule->end_date. ' for room(s) ';
    		foreach($customerPaymentSchedule->customerContract->rooms as $room)
    		{
    			$paymentDesc .= $room->room->number.', ';
    		}
    	}
    	if($chargeableTypeCode == 1)
    	{
    		// Client
            $clientPaymentSchedule = ClientPaymentSchedule::with(['client', 'property'])->whereControlNumber($controlNumber)->first();
            if(!$clientPaymentSchedule)
            {
                return response([
                    'status' => 204,
                    'statusDesc' => 'Invalid payment reference number',
                    'data' => null,
                ], 400);
            }
            // Valid
            if($clientPaymentSchedule->paid_at)
            {
                return response([
                    'status' => 203,
                    'statusDesc' => 'Payment reference number already paid',
                    'data' => null,
                ], 400);                
            }            
            if($clientPaymentSchedule->expiry_date < Carbon::today())
            {
                return response([
                    'status' => 205,
                    'statusDesc' => 'Payment reference number has expired',
                    'data' => null,
                ], 400);
            }
            $payerName = $clientPaymentSchedule->client->name();
            $payerId = $clientPaymentSchedule->client->id;
            $amount = $clientPaymentSchedule->amount_to_be_paid;
            $paymentReference = $clientPaymentSchedule->control_number;
            $paymentType = $accountCode;
            $paymentDesc = 'Charge for '.$clientPaymentSchedule->property->name.' from '.$clientPaymentSchedule->start_date.' to '.$clientPaymentSchedule->end_date;     
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
            	'payerId' => $payerId,
            ],
        ], 200);    	
    }

    public function pay(Request $request)
    {
    	// Validate token

    	// Validate checksum

    	// Decode control number

    	$controlNumber = $request->paymentReference;
    	$initial = substr($controlNumber, 0, 2);
    	$accountCode = substr($controlNumber, 2,3);
    	$chargeableTypeCode = substr($controlNumber, 5,1);
    	$chargeableCode = substr($controlNumber, 6,3);
    	$chargeableRandom = substr($controlNumber, 9,14);

    	$receipt = null;

    	if($chargeableTypeCode == 0)
    	{
    		// Customer
    		$customerPaymentSchedule = CustomerPaymentSchedule::whereControlNumber($controlNumber)->first();
    		if($customerPaymentSchedule->paid_at)
    		{
		        return response([
		            'status' => 207,
		            'statusDesc' => 'Payment reference number already paid',
		            'data' => null,
		        ], 400);
    		}

    		$cp = CustomerPayment::whereTransactionReference($request->transactionRef)->first();
    		if($cp)
    		{
		        return response([
		            'status' => 206,
		            'statusDesc' => 'Duplicate entry',
		            'data' => [
		            	'receipt' => $cp->receipt_number,
		            ],
		        ], 400);    			
    		}

    		$receipt = $this->generateReceiptNumber();

    		$customerPayment = new CustomerPayment;
    		$customerPayment->customer_payment_schedule_id = $customerPaymentSchedule->id;
    		$customerPayment->payer_name = $request->payerName;
    		$customerPayment->amount = $request->amount;
    		$customerPayment->amount_type = $request->amountType;
    		$customerPayment->currency = $request->currency;
    		$customerPayment->payment_reference = $request->paymentReference;
    		$customerPayment->payment_type = $request->paymentType;
    		$customerPayment->payment_description = $request->paymentDesc;
    		$customerPayment->payer_id = $request->payerID;
    		$customerPayment->transaction_reference = $request->transactionRef;
    		$customerPayment->transaction_channel = $request->transactionChannel;
    		$customerPayment->transaction_date = Carbon::parse($request->transactionDate);
    		$customerPayment->token = $request->token;
    		$customerPayment->checksum = $request->checksum;
    		$customerPayment->institution_id = $request->institutionID;
    		$customerPayment->receipt_number = $receipt;
    		$customerPayment->save();

    		$customerPaymentSchedule->paid_at = Carbon::now();
    		$customerPaymentSchedule->save();

    		$invoice = $customerPaymentSchedule->invoices()->whereActive(true)->first();
    		$invoice->paid_at = Carbon::now();
            $invoice->active = true;
    		$invoice->save();

            $paymentMode =  $customerPaymentSchedule->customerContract->property->propertyPaymentModes()->latest()->first()->paymentMode;
            // 03 - commission percentage
            if($paymentMode->code == '03')
            {
                // generate client payment schedule
                $this->generateClientPaymentSchedule($customerPayment); 
            }           
    	}
    	if($chargeableTypeCode == 1)
    	{
    		// Client
            $clientPaymentSchedule = ClientPaymentSchedule::whereControlNumber($controlNumber)->first();
            if($clientPaymentSchedule->paid_at)
            {
                return response([
                    'status' => 207,
                    'statusDesc' => 'Payment reference number already paid',
                    'data' => null,
                ], 400);
            }

            $cp = ClientPayment::whereTransactionReference($request->transactionRef)->first();
            if($cp)
            {
                return response([
                    'status' => 206,
                    'statusDesc' => 'Duplicate entry',
                    'data' => [
                        'receipt' => $cp->receipt_number,
                    ],
                ], 400);                
            }

            $receipt = $this->generateReceiptNumber();

            $clientPayment = new ClientPayment;
            $clientPayment->client_payment_schedule_id = $clientPaymentSchedule->id;
            $clientPayment->payer_name = $request->payerName;
            $clientPayment->amount = $request->amount;
            $clientPayment->amount_type = $request->amountType;
            $clientPayment->currency = $request->currency;
            $clientPayment->payment_reference = $request->paymentReference;
            $clientPayment->payment_type = $request->paymentType;
            $clientPayment->payment_description = $request->paymentDesc;
            $clientPayment->payer_id = $request->payerID;
            $clientPayment->transaction_reference = $request->transactionRef;
            $clientPayment->transaction_channel = $request->transactionChannel;
            $clientPayment->transaction_date = Carbon::parse($request->transactionDate);
            $clientPayment->token = $request->token;
            $clientPayment->checksum = $request->checksum;
            $clientPayment->institution_id = $request->institutionID;
            $clientPayment->receipt_number = $receipt;
            $clientPayment->save();

            $clientPaymentSchedule->paid_at = Carbon::now();
            $clientPaymentSchedule->save();

            $invoice = $clientPaymentSchedule->invoices()->whereActive(true)->first();
            $invoice->paid_at = Carbon::now();
            $invoice->active = true;
            $invoice->save();                      
    	}

        return response([
            'status' => 200,
            'statusDesc' => 'success',
            'data' => [
            	'receipt' => $receipt,
            ],
        ], 200);    	
    }

    private function generateReceiptNumber()
    {
    	$customSequence = CustomSequence::first();
    	if(!$customSequence)
    	{
    		$cs = new CustomSequence;
    		$cs->receipt_number = 0;
    		$cs->save();

    		$customSequence = CustomSequence::first();
    	}

    	$receiptNumber = $customSequence->receipt_number + 1;

    	$paddedReceiptNumber = sprintf('%010d', $receiptNumber);

    	$controlCode = 'RC-'.substr($paddedReceiptNumber, 0, 5).'-'.substr($paddedReceiptNumber, 5, 10);

    	$customSequence->receipt_number = $receiptNumber;
    	$customSequence->save();

    	return $controlCode;
    }

    private function generateClientPaymentSchedule(CustomerPayment $customerPayment)
    {

        $property = $customerPayment->customerPaymentSchedule->customerContract->property;

        if($property->commision > 0){
            $client = $customerPayment->customerPaymentSchedule->customerContract->property->client;

            $clientPaymentSchedule = new ClientPaymentSchedule;
            $clientPaymentSchedule->start_date = $customerPayment->transaction_date;
            $clientPaymentSchedule->end_date = $customerPayment->transaction_date->addMonth();
            $clientPaymentSchedule->expiry_date = $customerPayment->transaction_date->addMonth();
            $clientPaymentSchedule->client_id = $client->id;
            $clientPaymentSchedule->expiry_date = $customerPayment->transaction_date->addMonth();
            $clientPaymentSchedule->amount_to_be_paid = ($property->commision/100)*$customerPayment->amount;
            $clientPaymentSchedule->currency = $customerPayment->currency;

            $initial = config()->get('pms.control_number.initial');
            $accountCode = $client->accounts->first()->code;
            $chargeableCode = 1;
            $clientCode = $client->code;
            $clientRandom = sprintf('%06d', rand(1, 99999));

            $control_number = $initial.''.$accountCode.''.$chargeableCode.''.$clientCode.''.$clientRandom;

            $clientPaymentSchedule->control_number = $control_number;

            $clientPaymentSchedule->save();

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

            $clientPaymentSchedule->invoices()->save($invoice);
        }
    }
}
