<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function customerType()
    {
    	return $this->belongsTo(CustomerType::class);
    }

    public function customerContracts()
    {
    	return $this->hasMany(CustomerContract::class);
    }
}
