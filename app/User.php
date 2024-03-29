<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';

    const ADMIN_TYPE = 'admin';
    const DEFAULT_TYPE = 'default';
    

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'auth_token', 'referrer_id', 'referral_link', 'referral_count', 'ethereum_wallet_pub', 'bitcoin_wallet_pub' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey(); // Eloquent Model method
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function campaigns() {
        return $this->belongsToMany('App\Campaign', 'user_campaign', 'user_id', 'campaign_id');
    }

    public function referrals() {
        return $this->hasMany('App\Referral', 'referrer_id');
    }

    public function isAdmin()    {        
        return $this->type === self::ADMIN_TYPE;    
    }

}
