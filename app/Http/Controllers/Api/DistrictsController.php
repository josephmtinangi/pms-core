<?php

namespace App\Http\Controllers\Api;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistrictsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::with('region')->get();

        return response([
            'status' => 'success',
            'data' => $districts,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wards($id)
    {
        $district = District::find($id);

        if(!$district) {
            return response([
                'status' => 400,
                'statusText' => 'error',
                'message' => 'Not found',
                'ok' => true,
                'data' => $district,
            ], 400);            
        }

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $district->wards,
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
        $district = new District;
        $district->name = $request->name;
        $district->region_id = $request->region_id;
        $district->save();

        return response([
            'status' => 'success',
            'data' => $district,
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
