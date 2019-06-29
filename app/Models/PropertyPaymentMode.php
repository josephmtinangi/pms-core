<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPaymentMode extends Model
{
    public function paymentMode()
    {
    	return $this->belongsTo(PaymentMode::class);
    }
}
