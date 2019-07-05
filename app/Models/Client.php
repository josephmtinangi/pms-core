<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $hidden = [
        'password', 'remember_token',
    ];
        
    public function clientType()
    {
    	return $this->belongsTo(ClientType::class);
    }

    public function accounts()
    {
        return $this->morphMany('App\Models\Account', 'accountable');
    }   

    public function properties()
    {
    	return $this->hasMany(Property::class);
    }

    public function rentedProperties()
    {
        return $this->properties()->whereNotNull('rented_at');
    }

    public function vacantProperties()
    {
        return $this->properties()->whereNull('rented_at');
    }    

    public function name()
    {
        return $this->first_name.' '.$this->middle_name.' '.$this->last_name;
    } 

    public function clientPaymentSchedules()
    {
      return $this->hasMany(ClientPaymentSchedule::class);
    }     
}
