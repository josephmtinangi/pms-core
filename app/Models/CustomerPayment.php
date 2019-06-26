<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{

	protected $dates = [
		'transaction_date'
	];


    public function customer()
    {
    	return $this->belongsTo(Customer::class, 'payer_id');
    }

    public function customerPaymentSchedule()
    {
    	return $this->belongsTo(CustomerPaymentSchedule::class);
    }    
}
