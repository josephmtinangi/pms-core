<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function register(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|unique:users'
		]);

	    $user = new User;
	    $user->email = $request->email;
	    $user->name = $request->name;
	    $user->password = bcrypt($request->password);
	    $user->save();
	    
	    return response([
	        'status' => 'success',
	        'data' => $user
	    ], 200);
	}

	public function login(Request $request)
	{
	    $credentials = $request->only('email', 'password');
	    if ( ! $token = JWTAuth::attempt($credentials)) {
	            return response([
	                'status' => 400,
	                'statusText' => 'error',
	                'ok' => false,
	                'message' => 'Invalid Credentials.'
	            ], 400);
	    }
	    $user = User::whereEmail($request->email)->first();
	    $user->login_count = $user->login_count + 1;
	    $user->save();
	    
	    return response([
	    			'status' => 200,
	    			'statusText' => 'success',
	    			'ok' => true,
	    			'message' => '',
	            	'token' => $token,
	            	'user' => $user,
		], 200);
	}	

	public function user(Request $request)
	{
	    $user = User::find(Auth::user()->id);
	    return response([
	            'status' => 'success',
	            'data' => $user
	        	]);
	}

	public function refresh()
	{
	    return response([
	            'status' => 'success'
	        	]);
	}	

	public function logout()
	{
	    JWTAuth::invalidate();
	    return response([
	            'status' => 'success',
	            'message' => 'Logged out Successfully.'
	        ], 200);
	}

}
