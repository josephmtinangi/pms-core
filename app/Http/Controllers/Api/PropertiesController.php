<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\PropertyPaymentMode;
use App\Models\ClientPaymentSchedule;
use App\Http\Controllers\Controller;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::with(['propertyType', 'client', 'client.clientType', 'village', 'rooms'])->latest()->paginate(20);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $properties,
        ], 200);
    }

    public function all()
    {
        $properties = Property::latest()->get();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $properties,
        ], 200);
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $paymentMode = PaymentMode::find($request->payment_mode_id);

        if(!$paymentMode)
        {
            return response([
                'status' => 400,
                'statusText' => 'Bad request',
                'message' => 'Payment mode not found',
                'ok' => true,
                'data' => $property,
            ], 400);            
        }

        $client = Client::find($request->client_id);

        if(!$client)
        {
            return response([
                'status' => 400,
                'statusText' => 'Bad request',
                'message' => 'Client not found',
                'ok' => true,
                'data' => $client,
            ], 400);            
        }        

        $property = new Property;
        $property->name = $request->name;
        $property->property_type_id = $request->property_type_id;
        $property->client_id = $client->id;
        $property->physical_address = $request->physical_address;
        $property->floors = $request->floors;
        $property->village_id = $request->village_id;
        $property->save();

        $propertyPaymentMode = new PropertyPaymentMode;
        $propertyPaymentMode->property_id = $property->id;
        $propertyPaymentMode->payment_mode_id = $paymentMode->id;
        $propertyPaymentMode->amount = $request->amount;
        $propertyPaymentMode->save();

        // 01 - fixed amount per property
        if($paymentMode->code == '01')
        {
            $clientPaymentSchedule = new ClientPaymentSchedule;
            $clientPaymentSchedule->start_date = $request->start_date;
            $clientPaymentSchedule->expiry_date = $request->start_date->addMonth();
            $clientPaymentSchedule->end_date = $request->end_date;
            $clientPaymentSchedule->client_id = $client->id;
            $clientPaymentSchedule->property_id = $property->id;
            $clientPaymentSchedule->expiry_date = Carbon::now()->addMonth();
            $clientPaymentSchedule->amount_to_be_paid = $request->amount;

            $initial = config()->get('pms.control_number.initial');
            $accountCode = $client->accounts->first()->code;
            $chargeableCode = config()->get('pms.control_number.client');;
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

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $property,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $property = Property::with(['client', 'client.clientType', 'propertyType', 'rooms'])->find($id);

        if(!$property)
        {
            return response([
                'status' => 400,
                'statusText' => 'Bad request',
                'message' => 'Not found',
                'ok' => true,
                'data' => $property,
            ], 400);            
        }

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $property,
        ], 200);        
    }

    public function rooms($id)
    {
        $property = Property::with('rooms')->find($id);

        if(!$property)
        {
            return response([
                'status' => 200,
                'statusText' => 'error',
                'message' => 'Not found',
                'ok' => true,
                'data' => $property,
            ], 200);            
        }

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $property->rooms,
        ], 200);        
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $property = Property::find($id);

        if(!$property)
        {
            return response([
                'status' => 400,
                'statusText' => 'error',
                'message' => 'Not found',
                'ok' => true,
                'data' => $property,
            ], 400);            
        }

        $property->name = $request->name;
        $property->property_type_id = $request->property_type_id;
        $property->commision = $request->commision;
        $property->client_id = $request->client_id;
        $property->physical_address = $request->physical_address;
        $property->floors = $request->floors;
        $property->village_id = $request->village_id;
        $property->save();        

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $property,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
