<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    public function customer()
    {
    	return $this->belongsTo(Customer::class, 'payer_id');
    }
}
