<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{	
    protected $table = 'campaigns';


	protected $fillable = [
        'id', 'title', 'description', 'website', 'created_at', 'updated_at'
    ];


}
