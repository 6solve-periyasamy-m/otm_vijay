<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model implements AuthAuthenticatable
{
    use Authenticatable;

    public $additional_attributes = ['customer_full_name'];
	public function getCustomerFullNameAttribute()
	    {
		return "{$this->first_name} {$this->last_name}";
	    }
}

