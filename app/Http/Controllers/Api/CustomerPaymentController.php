<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use App\Http\Controllers\Controller;

class CustomerPaymentController extends Controller
{
    public function index()
    {
    	$customerPayments = CustomerPayment::latest()->paginate(100);
    	
        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $customerPayments,
        ], 200);    	
    }
}
