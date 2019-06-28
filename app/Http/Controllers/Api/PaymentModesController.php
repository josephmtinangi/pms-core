<?php

namespace App\Http\Controllers\Api;

use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentModesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentModes = PaymentMode::latest()->get();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $paymentModes,
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
        $paymentMode = new PaymentMode;
        $paymentMode->name = $request->name;
        $paymentMode->description = $request->description;
        if(!PaymentMode::latest()->first())
        {
            $paymentMode->code = sprintf('%02d', 1);
        }
        else
        {
            $paymentMode->code = sprintf('%02d', PaymentMode::latest()->first()->code + 1);
        }
        $paymentMode->save();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $paymentMode,
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
        //
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
        //
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
