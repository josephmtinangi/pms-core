<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
    public function clients()
    {
    	return $this->hasMany(Client::class);
    }
}
