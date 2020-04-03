<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Referral;

class InviteController extends Controller
{	
    function invite_friend(Request $request){
    	$user = User::where('email', $request->email)->first();
        $link = $user->referral_link;
        $friendArr = $request->friendEmail;
        for ($i=0; $i < count($friendArr); $i++) { 
        	Mail::to($friendArr[$i])->send(new SendMailable( $request->name, $link ));

        	$ref = new Referral();
        	$ref->referrer_id = $user->id;
        	$ref->user_email = $friendArr[$i];
        	$ref->registered = false;
        	$ref->save();

        }
        return 'Email was sent';
    }


    function get_referrals(Request $request) {
        $user = User::where('auth_token', $request->auth_token)->first();
    	return $user->referrals;
    }

}