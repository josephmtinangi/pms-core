<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContract extends Model
{
	protected $dates = [
		'start_date',
		'end_date',
	];

    public function customer()
    {
    	return $this->belongsTo(Customer::class);
    }

    public function property()
    {
    	return $this->belongsTo(Property::class);
    }

    public function controlNumbers()
    {
        return $this->hasMany(CustomerPaymentSchedule::class, 'customer_contract_id');
    }

    public function rooms()
    {
        return $this->hasMany(CustomerContractRoom::class);
    }
}
