<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPaymentSchedule extends Model
{
    public function invoices()
    {
        return $this->morphMany('App\Models\Invoice', 'invoiceable');
    }

    public function client()
    {
    	return $this->belongsTo(Client::class);
    }
}
