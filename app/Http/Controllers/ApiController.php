<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Validator;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    function signup(Request $r){

        $validator = Validator::make(
            [
                'first_name' => $r->first_name,
                'last_name' => $r->last_name,
                'email' => $r->email,
                'password' =>$r->password,
            ],
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]
        );
    	
    	

        if ($validator->fails())
        {
    		return [
    			'error' => true,
    			'reason' => $validator->errors()->first(),
    		];
           
        } else {

            $new = new Users();
            $new->first_name = $r->first_name;
            $new->last_name = $r->last_name;
            $new->email = $r->email;
            $new->password = Hash::make($r->password);
            $new->save();

            $current_user = Users::where('email', '=', $new->email)->get();
            
            return $current_user;
        }

        
    }

    function login (Request $request) {
        $email = $request->email;
        $password = $request->password;

        if (Users::where([['email', '=', $email]])->exists()) {
            $user = Users::where('email', '=', $email)->get();
            $errObj = [
            	'error' => true,
            	'reason' => 'This email is already used',
            ];
            return Hash::check($password, $user[0]->password) ? $user : $errObj;
        }
        else {
        	$errObj = [
        		'error' => true,
            	'reason' => 'Please create an account first',
        	];
            return $errObj;
        }
    }

}