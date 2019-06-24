<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function accountable()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
        	if(!$model->code)
        	{
        		$model->code = sprintf('%03d', 1);
        	}
        	else
        	{
            	$model->code = sprintf('%03d', (int)($model->code) + 1);
        	}
        });
    }    
}
