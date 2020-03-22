<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\UserCampaign;
use App\User;



class CampaignController extends Controller
{
    function complete_campaign(Request $request) {

        $new = new UserCampaign();
        $new->user_id = $request->user_id;
        $new->campaign_id = $request->campaign_id;
        $new->save();

    }

    function campaign_progress(Request $request) {

        $user = User::where('auth_token', $request->auth_token)->first();

        $user_campaigns = UserCampaign::where('user_id', $user->id)->get();
        $all_campaigns = Campaign::all();

        return [
        	'success' => true,
        	'completed' => count($user_campaigns),
        	'remaining' => count($all_campaigns) - count($user_campaigns),
        ];

    }

}
