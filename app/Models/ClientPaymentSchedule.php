<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPaymentSchedule extends Model
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

    public function client()
    {
    	return $this->belongsTo(Client::class);
    }

    public function property()
    {
    	return $this->belongsTo(Property::class);
    }
}
