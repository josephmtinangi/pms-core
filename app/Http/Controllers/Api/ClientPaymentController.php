<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ClientPayment;
use App\Http\Controllers\Controller;

class ClientPaymentController extends Controller
{
    public function index()
    {
    	$clientPayments = ClientPayment::with('client')->latest()->paginate(100);
    	
        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $clientPayments,
        ], 200);    	
    }
}
