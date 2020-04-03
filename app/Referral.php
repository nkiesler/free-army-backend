<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'referrals';


	protected $fillable = [
        'id', 'referrer_id', 'user_email', 'registered', 'created_at', 'updated_at'
    ];

    public function referrer() {
	    return $this->belongsTo('App\User');
	}

}
