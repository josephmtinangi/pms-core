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

    public function schedules()
    {
        return $this->customerContracts()->with(['controlNumbers.billType']);
    }

    public function name()
    {
    	return $this->first_name.' '.$this->middle_name.' '.$this->last_name;
    }
}
