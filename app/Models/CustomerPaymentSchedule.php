<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPaymentSchedule extends Model
{
    protected $dates = [
    	'start_date',
    	'end_date',
    	'expiry_date',
    ];

    public function invoices()
    {
        return $this->morphMany('App\Models\Invoice', 'invoiceable');
    }    

    public function customerContract()
    {
    	return $this->belongsTo(CustomerContract::class);
    }

    public function customerPayment()
    {
        return $this->hasOne(CustomerPayment::class);
    }
}
