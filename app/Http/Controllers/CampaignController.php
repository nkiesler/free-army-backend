<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\UserCampaign;
use App\User;



class CampaignController extends Controller
{	

	function get_campaigns(Request $request) {
        $user = User::where('auth_token', $request->auth_token)->first();
        $user_campaigns = $user->campaigns;
        $all_campaigns = Campaign::all();

        foreach ($all_campaigns as $campaign) {
        	foreach ($user_campaigns as $completed) {
        		if ($campaign['id'] == $completed['id']) {
        			$campaign['completed'] = true;
        		} else {
        			$campaign['completed'] = false;
        		}
        	}
        }

        return $all_campaigns;
	}

    function complete_campaign(Request $request) {

        $new = new UserCampaign();
        $new->user_id = $request->user_id;
        $new->campaign_id = $request->campaign_id;
        $new->save();

    }

    function campaign_progress(Request $request) {

        $user = User::where('auth_token', $request->auth_token)->first();

        $user_campaigns = $user->campaigns;
        $all_campaigns = Campaign::all();

        return [
        	'success' => true,
        	'completed' => count($user_campaigns),
        	'remaining' => count($all_campaigns) - count($user_campaigns),
        ];

    }

}
