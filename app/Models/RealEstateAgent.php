<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealEstateAgent extends Model
{
    public function accounts()
    {
        return $this->morphMany('App\Models\Account', 'accountable');
    }
}
