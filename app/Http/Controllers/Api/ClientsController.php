<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::with('clientType')->latest()->paginate(20);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $clients,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $clients = Client::latest()->get();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $clients,
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
        $client = new Client;
        $client->first_name = $request->first_name;
        $client->middle_name = $request->middle_name;
        $client->last_name = $request->last_name;
        $client->client_type_id = $request->client_type_id;
        $client->phone = $request->phone;
        $client->photo = $request->photo;
        $client->email = $request->email;
        $client->physical_address = $request->physical_address;
        $client->postal_address = $request->postal_address;
        $client->tin = $request->tin;

        $client->save();
        
        $account = new Account;
        $account->name = $request->account_name;
        $account->number = $request->account_number;

        $client->accounts()->save($account);


        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $client,
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
