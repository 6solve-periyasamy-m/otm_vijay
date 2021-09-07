<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Customer extends Model implements AuthAuthenticatable
{
    use Authenticatable;
    use SoftDeletes;
    use HasFactory;

    public $additional_attributes = ['customer_full_name'];
    public $full_name;

    public function getFullName()
    {
        $this->full_name = $this->first_name . ' ' . $this->last_name;
        return $this->full_name;
    }

    public function getCustomerFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

