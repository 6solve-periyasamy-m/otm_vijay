<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Notifications\CustomerResetPassword;
use App\Repository\CustomerAuthenticationRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Cashier\Billable;


class Customer extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    use Notifiable;
    use Billable;

    protected $guard = 'customer';

    public $additional_attributes = ['customer_full_name', 'full_name'];

    protected $fillable = ['title', 'first_name', 'middle_names', 'last_name', 'date_of_birth', 'mobile_number', 'other_phone_number', 'email_address', 'password', 'gender', 'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_telephone', 'passport_first_name', 'passport_middle_name', 'passport_last_name', 'passport_number', 'passport_issue_date', 'passport_expiry_date','passport_country_of_issue', 't_shirt_size_id', 'hat_size_id', 'notes', 'loyalty_number', 'login_token', 'home_address_id', 'billing_address_id',];

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
        ];
    }

    public function getUpdateValidationRules()
    {
        return [
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'mobile_number' => 'required',
            'email_address' => [
                'nullable',
                'email',
                Rule::unique('customers','email_address')->ignore($this->id),
            ],
        ];
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
        return $this->belongsToMany(Order::class, OrderCustomer::class, 'customer_id', 'order_id');
    }

    public function routeNotificationForMail($notification = null)
    {
        return [$this->email_address => $this->full_name,];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPassword($token));
    }

    public function getEmailForPasswordReset()
    {
        return $this->email_address;
    }

    public function tokens()
    {
        return $this->hasMany(CustomerApiToken::class, 'customer_id');
    }

    public function getCurrentToken(): CustomerApiToken
    {
        return CustomerAuthenticationRepository::getLatestToken($this);
    }

    public function generateToken(int $expiresIn = ApiToken::DEFAULT_EXPIRY): CustomerApiToken
    {
        return CustomerAuthenticationRepository::generateUserToken($this, $expiresIn);
    }

    public function invalidateAllTokens()
    {
        CustomerAuthenticationRepository::invalidateAllUserTokens($this);
    }

    public function purgeTokens(int $limit = ApiToken::DEFAULT_LIMIT)
    {
        CustomerAuthenticationRepository::purgeUserTokens($this, $limit);
    }

    public function leadingOrders()
    {
        return $this->hasManyThrough(Order::class, OrderCustomer::class, 'customer_id', 'lead_booker_id');
    }
}
