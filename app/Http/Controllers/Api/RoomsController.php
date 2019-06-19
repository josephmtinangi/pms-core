<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::with('property')->latest()->paginate(20);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $rooms,
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
        $room = new Room();
        $room->number = $request->number;
        $room->floor = $request->floor;
        $room->metrics = $request->metrics;
        $room->size = $request->size;
        $room->price_per_sqm = $request->price_per_sqm;
        $room->currency = $request->currency;
        $room->property_id = $request->property_id;
        $room->save();

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => $room,
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

    public function upload(Request $request)
    {
        $property = Property::find($request->property_id);

        if(!$property)
        {
            return response([
                'status' => 400,
                'statusText' => 'error',
                'ok' => true,
                'message' => 'Property not found',
                'data' => null,
            ], 400);
        }

        $count = 0;

        if (request()->hasFile('file')) {
            if (request()->file('file')->isValid()) {
                
                $path = $request->file->store('files');
                $path = storage_path() . '/app/public/' . $path;
                $rows = \Excel::load($path, function ($reader) {

                })
                ->get();

                foreach($rows as $key => $row) {
                    $room = new Room();
                    $room->number = $row->number;
                    $room->floor = $row->floor;
                    $room->metrics = $row->metrics;
                    $room->size = $row->size;
                    $room->price_per_sqm = $row->price_per_sqm;
                    $room->currency = $row->currency;
                    $room->property_id = $property->id;
                    $room->save();

                    $count++;
                }
            }
        }

        return response([
            'status' => 200,
            'statusText' => 'success',
            'ok' => true,
            'data' => [
                'rooms' => $count,
            ],
        ], 200);        
    }
}
