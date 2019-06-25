<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoicesController extends Controller
{
    public function index()
    {
    	$invoices = Invoice::latest()->paginate(100);

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $invoices,
        ], 200);
    }

    public function show($id)
    {
    	$invoice = Invoice::find($id);

    	if(!$invoice)
    	{
	        return response([
	            'status' => 400,
	            'statusText' => 'error',
	            'message' => 'Not found',
	            'ok' => true,
	            'data' => $invoice,
	        ], 400);
    	}

        return response([
            'status' => 200,
            'statusText' => 'success',
            'message' => '',
            'ok' => true,
            'data' => $invoice,
        ], 200);
    }
}
