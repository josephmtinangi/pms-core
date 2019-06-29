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

    public function village()
    {
    	return $this->belongsTo(Village::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function propertyPaymentModes()
    {
        return $this->hasMany(PropertyPaymentMode::class);
    }
}
