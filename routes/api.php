<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('signup', 'ApiController@signup');
Route::post('login', 'ApiController@login');
Route::post('update_settings', 'ApiController@update_settings');
Route::post('invite_friend', 'InviteController@invite_friend');
Route::post('change_password', 'ApiController@change_password');
Route::post('verify_account', 'ApiController@verify_account');

Route::post('get_campaigns', 'CampaignController@get_campaigns');
Route::post('complete_campaign', 'CampaignController@complete_campaign');
Route::post('campaign_progress', 'CampaignController@campaign_progress');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
