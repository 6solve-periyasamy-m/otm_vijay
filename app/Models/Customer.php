<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;


class Customer extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    use Notifiable;
    use Billable;

    protected $guard = 'customer';

    public $additional_attributes = ['customer_full_name', 'full_name'];

    protected $fillable = ['title', 'first_name', 'middle_names', 'last_name', 'date_of_birth',
        'mobile_number', 'other_phone_number', 'email_address',
        'password', 'gender', 
        'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_telephone',
        'passport_first_name', 'passport_middle_name', 'passport_last_name', 'passport_number', 'passport_issue_date', 'passport_expiry_date',
        't_shirt_size_id', 'hat_size_id',
        'notes', 'loyalty_number',
        'login_token',
        'home_address_id', 'billing_address_id',];

    protected $casts = ['date_of_birth' => 'date', 'passport_issue_date' => 'date', 'passport_expiry_date' => 'date',];

    protected $hidden = ['password', 'pm_type', 'pm_last_four', 'trial_ends_at'];

    public static function getValidationRules()
    {
        return [
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'mobile_number' => 'required',
            'email_address' => 'nullable|email|unique:customers,email_address',
            'gender' => 'required',
            'emergency_contact_name' => 'required',
            'emergency_contact_relationship' => 'required',
            'emergency_contact_telephone' => 'required',
        ];
    }

    public function getFields()
    {
	    $fields = $this->fillable;
	    return $fields;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getCustomerFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameAttribute(): string
    {
        return $this->getFullName();
    }

    public function homeAddress()
    {
        return $this->belongsTo(Address::class, 'home_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function tShirtSize()
    {
        return $this->belongsTo(TShirtSize::class, 't_shirt_size_id');
    }

    public function hatSize()
    {
        return $this->belongsTo(HatSize::class, 'hat_size_id');
    }

    public function orderCustomers()
    {
        return $this->hasMany(OrderCustomer::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderCustomer::class, 'customer_id', 'id');
    }
}
