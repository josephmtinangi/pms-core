<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public function propertyType()
    {
    	return $this->belongsTo(PropertyType::class);
    }

    public function client()
    {
    	return $this->belongsTo(Client::class);
    }
}
