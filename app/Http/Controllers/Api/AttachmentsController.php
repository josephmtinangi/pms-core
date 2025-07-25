<?php

namespace App\Http\Controllers\Api;

use File;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttachmentsController extends Controller
{
    public function index($category, $filename)
    {
    	$path = public_path().'\storage'.'\\'.$category.'\\'.$filename;

	    if (!File::exists($path))
	    {
	        abort(404);
	    }

    	$file = File::get($path);
    	$type = File::mimeType($path);

	    $response = Response::make($file, 200);

	    $response->header("Content-Type", $type);

	    return $response;    	    
    }
}
