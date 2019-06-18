<?php

namespace App\Http\Controllers\Api;

use App\Models\Ward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wards = Ward::with('district')->get();

        return response([
            'status' => 'success',
            'data' => $wards,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function villages($id)
    {
        $ward = Ward::find($id);

        if(!$ward) {
            return response([
                'status' => 400,
                'statusText' => 'error',
                'message' => 'Not found',
                'ok' => true,
                'data' => $ward,
            ], 400);            
        }

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $ward->villages,
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
        $ward = new Ward;
        $ward->name = $request->name;
        $ward->district_id = $request->district_id;
        $ward->save();

        return response([
            'status' => 'success',
            'data' => $ward,
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
