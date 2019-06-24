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
}
