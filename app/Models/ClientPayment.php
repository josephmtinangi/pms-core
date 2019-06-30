<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
	protected $dates = [
		'transaction_date',
	];

    public function client()
    {
    	return $this->belongsTo(Client::class);
    }

    public function clientPaymentSchedule()
    {
    	return $this->belongsTo(ClientPaymentSchedule::class);
    }     
}
