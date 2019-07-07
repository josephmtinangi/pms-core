<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\BillType;
use App\Models\Customer;
use App\Utilities\Helper;
use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Models\PaymentReference;
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

        $customer = Customer::find($request->customer_id);

        if(!$customer)
        {
            return response([
                'status' => 200,
                'statusText' => 'Bad request',
                'message' => 'Customer not found',
                'ok' => false,
                'data' => null,
            ], 200); 
        }

    	$lease = CustomerContract::with('property')->whereCustomerId($customer->id)->whereActive(true)->first();

        if(!$lease)
        {
            return response([
                'status' => 400,
                'statusText' => 'Bad request',
                'message' => 'Lease not found',
                'ok' => false,
                'data' => null,
            ], 400); 
        }        

        $billType = BillType::first();
        if($request->bill_type_id)
        {
            $bt = BillType::find($request->bill_type_id);
            if($bt)
            {
                $billType = $bt;
            }
        }

    	$existingCustomerPaymentSchedule = CustomerPaymentSchedule::whereBillTypeId($billType->id)->whereCustomerContractId($lease->id)->whereActive(true)->first();

    	$customerPaymentSchedule = new CustomerPaymentSchedule;
    	$customerPaymentSchedule->bill_type_id = $billType->id;
        $customerPaymentSchedule->customer_contract_id = $lease->id;
		$customerPaymentSchedule->start_date = Carbon::parse($request->start_date);
		$customerPaymentSchedule->end_date = Carbon::parse($request->end_date);
		$customerPaymentSchedule->expiry_date = Carbon::parse($request->start_date)->addDays(7);
		$customerPaymentSchedule->amount_to_be_paid = $request->amount;
    	if(!$existingCustomerPaymentSchedule)
    	{
    		$customerPaymentSchedule->active = true;
    	}
    	else
    	{
    		$customerPaymentSchedule->active = true;

    		$existingCustomerPaymentSchedule->active = false;
    		$existingCustomerPaymentSchedule->save();
    	}

        $control_number = null;
        $unique = false;
        do {

            $number = Helper::generateCustomerControlNumber($lease);

            try {

                $paymentReference = new PaymentReference;
                $paymentReference->number = $number;
                $paymentReference->save();

                $control_number = $paymentReference->number;

                $unique = true;
            }catch(Exception $e){
                $unique = false;
            }
        }while(!$unique);

		$customerPaymentSchedule->control_number = $control_number;

    	$customerPaymentSchedule->save();

        // Generate invoice
        $invoice = new Invoice;
        $invoice->property_id = $lease->property->id;
        if(!Invoice::wherePropertyId($lease->property->id)->latest()->first())
        {
            $invoice->number = sprintf('%06d', 1);
        }
        else
        {
            $invoice->number = sprintf('%06d', ((int)Invoice::wherePropertyId($lease->property->id)->latest()->first()->number) + 1);
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
