<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\RealEstateAgent;
use App\Http\Controllers\Controller;

class RealEstateAgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $realEstateAgents = RealEstateAgent::get();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $realEstateAgents,
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
        $realEstateAgent = new RealEstateAgent;
        $realEstateAgent->name = $request->name;
        $realEstateAgent->logo = $request->logo;
        $realEstateAgent->postal_address = $request->postal_address;
        $realEstateAgent->physical_address = $request->physical_address;
        $realEstateAgent->phone = $request->phone;
        $realEstateAgent->email = $request->email;
        $realEstateAgent->save();

        $account = new Account;
        $account->name = $request->account_name;
        $account->number = $request->account_number;

        if(!Account::latest()->first())
        {
            $account->code = sprintf('%03d', 1);
        }
        else
        {
            $account->code = sprintf('%03d', ((int)Account::latest()->first()->code)+1);
        }

        $realEstateAgent->accounts()->save($account);        

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $realEstateAgent,
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
        $realEstateAgent = RealEstateAgent::with('accounts')->find($id);

        if(!$realEstateAgent)
        {
            return response([
                'status' => 400,
                'statusText' => 'error',
                'message' => 'not found',
                'ok' => true,
                'data' => $realEstateAgent,
            ], 400);             
        }

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $realEstateAgent,
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
