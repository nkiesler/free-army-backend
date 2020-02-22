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
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
    	
    	

        if ($validator->fails())
        {
    		return 'validation failed';
           
        } else {

            $new = new Users();
            $new->first_name = $r->first_name;
            $new->last_name = $r->last_name;
            $new->email = $r->email;
            $new->password = Hash::make($r->password);
            $new->save();

            var_dump('user saved')

            $current_user = Users::where('email', '=', $new->email)->get();
            
            return $current_user;
        }

        
    }

    // function login(Request $r){
    //     $email = $r->email;
    //     $password = $r->password;

    //     if (Usermodel::where([['email', '=', $email]])->exists()) {
    //         $user = Usermodel::where('email', '=', $email)->get();
    //         if (Hash::check($password, $user[0]->password)){
    //             Session::put('user', $user);
    //             return Redirect::to("/profile");
    //         }
    //         else {
    //             return view('login')->withErrors('Your password is incorrect');
    //         }
    //     }
    //     else{
    //         return Redirect::to("/signup")->withErrors('Please sign up first');
    //     }
      
    // }
}
