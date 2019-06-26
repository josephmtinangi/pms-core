<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Property;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ClientPayment;
use App\Models\CustomerPayment;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => 'success',
            'ok' => true,
            'data' => [
            	'clients_count' => Client::count(),
            	'properties_count' => Property::count(),
            	'customers_count' => Customer::count(),
            	'customer_payment' => CustomerPayment::sum('amount'),
                'client_payment' => ClientPayment::sum('amount'),
            ],
        ], 200);    	
    }
}
