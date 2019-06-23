<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttachmentsController extends Controller
{
    public function index($category, $filename)
    {
    	$path = public_path().'\storage'.'\\'.$category.'\\'.$filename;

    	return response()->file($path);
    }
}
