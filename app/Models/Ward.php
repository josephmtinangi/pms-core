<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    public function district()
    {
    	return $this->belongsTo(Ward::class);
    }

    public function villages()
    {
    	return $this->hasMany(Village::class);
    }
}
