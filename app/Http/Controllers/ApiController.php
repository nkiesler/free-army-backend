<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use JWTAuthException;

class ApiController extends Controller
{   
    public $reff_id;
    private function getToken($email, $password) {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }

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

            if (!empty($r->reff)) {
                $reff_id = User::where(['email' => $r->reff])->first();
                User::where(['email' => $r->reff])->update(array('referral_count' => $reff_id->referral_count + 1));
            }
            $new = new User();
            $new->first_name = $r->first_name;
            $new->last_name = $r->last_name;
            $new->email = $r->email;
            $new->password = Hash::make($r->password);
            $new->auth_token = '';
            $new->referral_link = env('APP_URL') . '/sign-up?ref='. $r->email;
            $new->referrer_id = isset($reff_id) ? $reff_id->id : null;
            $current_user = $new->save();

            if ($current_user) {
                $token = self::getToken($r->email, $r->password);
                if (!is_string($token))  return response()->json(['success'=>false,'data'=>'Token generation failed'], 201);
                $current_user = User::where('email', '=', $new->email)->get()->first();
                $current_user->auth_token = $token;
                $current_user->save();
                $response = ['success'=>true, 'data'=>['first_name'=>$current_user->first_name, 'last_name' => $current_user->last_name ,'id'=>$current_user->id,'email'=>$current_user->email,'auth_token'=>$token]]; 
            } else {
                $response = ['success'=>false, 'reason'=>'Couldnt register user'];
            }

            return response()->json($response, 201);
        } 
    }

    function login (Request $request) {
        $email = $request->email;
        $password = $request->password;

        if (User::where([['email', '=', $email]])->exists()) {
            $user = User::where('email', '=', $email)->get()->first();
            if ($user && Hash::check($password, $user->password)) {
                $token = self::getToken($email, $password);
                $user->auth_token = $token;
                $user->save();
                $response = ['success'=>true, 'data'=>['id'=>$user->id,'auth_token'=>$user->auth_token,'first_name'=>$user->first_name, 'last_name'=>$user->last_name, 'email'=>$user->email, 'referral_link'=>$user->referral_link, 'created_at' => $user->created_at ]];
            }
            
        }
        else {
        	$response = [
        		'success' => false,
            	'reason' => 'Please create an account first',
        	];
        }

        return response()->json($response, 201);

    }

    function update_settings (Request $request) {
        $user = User::where('id', '=', $request->id)->get()->first();
        if ($user) {
            $user->update($request->all());
            return [
                'success' => true,
                'data'=>['id'=>$user->id,'auth_token'=>$user->auth_token,'first_name'=>$user->first_name, 'last_name'=>$user->last_name, 'email'=>$user->email]
            ];
        }

        return [
            'success' => false,
        ];
    }

    function change_password (Request $request) {
        $user = User::where('email', '=', $request->user_email)->get()->first();
        if ($user && Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return [
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
                'message' => "Password isn't correct",
            ];
        }

        return [
            'success' => false,
            'message' => 'Error',
        ];
    }

}