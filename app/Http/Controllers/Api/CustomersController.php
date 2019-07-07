<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with('customerType')->latest()->paginate(100);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $customers,
        ], 200);
    }

    public function all()
    {
        $customers = Customer::latest()->get();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $customers,
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
        $customer = new Customer;
        $customer->first_name = $request->first_name;
        $customer->middle_name = $request->middle_name;
        $customer->last_name = $request->last_name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->customer_type_id = $request->customer_type_id;
        $customer->physical_address = $request->physical_address;
        $customer->postal_address = $request->postal_address;
        $customer->tin = $request->tin;

        if(!Customer::latest()->first())
        {
            $customer->code = sprintf('%03d', 1);
        }
        else
        {
            $customer->code = sprintf('%03d', Customer::latest()->first()->code + 1);
        }

        $customer->save();
        
        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $customer,
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
        $customer = Customer::with(['customerType', 'customerContracts', 'schedules'])->find($id);

        if(!$customer)
        {
            return response([
                'status' => 400,
                'statusText' => 'Bad request',
                'message' => 'Not found',
                'ok' => false,
                'data' => $customer,
            ], 400);            
        }
        
        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $customer,
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
        $customer = Customer::find($id);

        if(!$customer)
        {
            return response([
                'status' => 400,
                'statusText' => 'Bad request',
                'message' => 'Not found',
                'ok' => true,
                'data' => $customer,
            ], 400);            
        }

        $customer->first_name = $request->first_name;
        $customer->middle_name = $request->middle_name;
        $customer->last_name = $request->last_name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->customer_type_id = $request->customer_type_id;
        $customer->physical_address = $request->physical_address;
        $customer->postal_address = $request->postal_address;
        $customer->tin = $request->tin;
        $customer->save();        
        
        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => $customer,
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
