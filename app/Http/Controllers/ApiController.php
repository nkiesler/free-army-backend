<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Validator;

class ApiController extends Controller
{
    function signup(Request $r){


        $validator = Validator::make(
            [
                'name' => $r->name,
                'surname' => $r->surname,
                'email' => $r->email,
                'password' =>$r->password,
            ],
            [
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
    	
    	

        if ($validator->fails())
        {
           // 
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
