<?php

namespace App\Models\Customer;

use App\Models\Location\Address;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\System\ApiToken;
use App\Models\System\CustomerApiToken;
use App\Notifications\CustomerResetPassword;
use App\Repository\CustomerAuthenticationRepository;
use Database\Factories\CustomerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;


/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string|null $email_address
 * @property string|null $password
 * @property string|null $email_verified_at
 * @property string|null $remember_token
 * @property string|null $login_token
 * @property string|null $gender
 * @property string|null $title
 * @property string $first_name
 * @property string|null $middle_names
 * @property string $last_name
 * @property Carbon $date_of_birth
 * @property string|null $mobile_number
 * @property string|null $other_phone_number
 * @property int $home_address_id
 * @property int $billing_address_id
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_relationship
 * @property string|null $emergency_contact_telephone
 * @property string|null $passport_first_name
 * @property string|null $passport_middle_name
 * @property string|null $passport_last_name
 * @property string|null $passport_number
 * @property Carbon|null $passport_issue_date
 * @property Carbon|null $passport_expiry_date
 * @property string|null $passport_country_of_issue
 * @property string|null $loyalty_number
 * @property string $profile_picture Asset link to profile picture
 * @property int|null $t_shirt_size_id
 * @property int|null $hat_size_id
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property string|null $dietary_notes
 * @property string|null $mobility_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $stripe_id Stripe customer ID
 * @property string|null $pm_type Stripe payment method type
 * @property string|null $pm_last_four Stripe payment method last four
 * @property string|null $trial_ends_at Stripe trial end date
 * @property-read Address $billingAddress Billing address. Should be a unique entry in the database
 * @property-read string $customer_full_name (Deprecated) Full name of the customer
 * @property-read string $full_name Full name of customer
 * @property-read HatSize|null $hatSize Customer hat size
 * @property-read Address $homeAddress Home address. Should be a unique entry in the database
 * @property-read Collection|Order[] $leadingOrders Orders where they are the lead booker
 * @property-read int|null $leading_orders_count Amount of orders where they are the lead booker
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications System notifications for customer
 * @property-read int|null $notifications_count Amount of system notifications for customer
 * @property-read Collection|OrderCustomer[] $orderCustomers OrderCustomers for this customer
 * @property-read int|null $order_customers_count Amount of OrderCustomers for this customer
 * @property-read Collection|Order[] $orders Orders for this customer
 * @property-read int|null $orders_count Amount of orders for this customer
 * @property-read Collection|Subscription[] $subscriptions Stripe Subscriptions
 * @property-read int|null $subscriptions_count Amount of Stripe Subscriptions
 * @property-read TShirtSize|null $tShirtSize Customer t-shirt size
 * @property-read Collection|CustomerApiToken[] $tokens Customer API tokens
 * @property-read int|null $tokens_count Amount of Customer API tokens
 * @method static CustomerFactory factory(...$parameters)
 * @method static Builder|Customer newModelQuery()
 * @method static Builder|Customer newQuery()
 * @method static QueryBuilder|Customer onlyTrashed()
 * @method static Builder|Customer query()
 * @method static Builder|Customer whereBillingAddressId($value)
 * @method static Builder|Customer whereCreatedAt($value)
 * @method static Builder|Customer whereDateOfBirth($value)
 * @method static Builder|Customer whereDeletedAt($value)
 * @method static Builder|Customer whereDietaryNotes($value)
 * @method static Builder|Customer whereEmailAddress($value)
 * @method static Builder|Customer whereEmailVerifiedAt($value)
 * @method static Builder|Customer whereEmergencyContactName($value)
 * @method static Builder|Customer whereEmergencyContactRelationship($value)
 * @method static Builder|Customer whereEmergencyContactTelephone($value)
 * @method static Builder|Customer whereExternalNotes($value)
 * @method static Builder|Customer whereFirstName($value)
 * @method static Builder|Customer whereGender($value)
 * @method static Builder|Customer whereHatSizeId($value)
 * @method static Builder|Customer whereHomeAddressId($value)
 * @method static Builder|Customer whereId($value)
 * @method static Builder|Customer whereInternalNotes($value)
 * @method static Builder|Customer whereLastName($value)
 * @method static Builder|Customer whereLoginToken($value)
 * @method static Builder|Customer whereLoyaltyNumber($value)
 * @method static Builder|Customer whereMiddleNames($value)
 * @method static Builder|Customer whereMobileNumber($value)
 * @method static Builder|Customer whereMobilityNotes($value)
 * @method static Builder|Customer whereOtherPhoneNumber($value)
 * @method static Builder|Customer wherePassportCountryOfIssue($value)
 * @method static Builder|Customer wherePassportExpiryDate($value)
 * @method static Builder|Customer wherePassportFirstName($value)
 * @method static Builder|Customer wherePassportIssueDate($value)
 * @method static Builder|Customer wherePassportLastName($value)
 * @method static Builder|Customer wherePassportMiddleName($value)
 * @method static Builder|Customer wherePassportNumber($value)
 * @method static Builder|Customer wherePassword($value)
 * @method static Builder|Customer wherePmLastFour($value)
 * @method static Builder|Customer wherePmType($value)
 * @method static Builder|Customer whereProfilePicture($value)
 * @method static Builder|Customer whereRememberToken($value)
 * @method static Builder|Customer whereStripeId($value)
 * @method static Builder|Customer whereTShirtSizeId($value)
 * @method static Builder|Customer whereTitle($value)
 * @method static Builder|Customer whereTrialEndsAt($value)
 * @method static Builder|Customer whereUpdatedAt($value)
 * @method static QueryBuilder|Customer withTrashed()
 * @method static QueryBuilder|Customer withoutTrashed()
 * @mixin Eloquent
 */
class Customer extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    use Notifiable;
    use Billable;

    protected string $guard = 'customer';

    protected $fillable = ['title', 'first_name', 'middle_names', 'last_name', 'date_of_birth', 'mobile_number', 'other_phone_number',
        'email_address', 'password', 'gender', 'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_telephone',
        'passport_first_name', 'passport_middle_name', 'passport_last_name', 'passport_number', 'passport_issue_date', 'passport_expiry_date',
        'passport_country_of_issue', 't_shirt_size_id', 'hat_size_id', 'notes', 'loyalty_number', 'login_token', 'home_address_id',
        'billing_address_id','internal_notes','external_notes','dietary_notes','mobility_notes'];

    protected $casts = ['date_of_birth' => 'date', 'passport_issue_date' => 'date', 'passport_expiry_date' => 'date',];

    protected $hidden = ['password', 'pm_type', 'pm_last_four', 'trial_ends_at'];

    public static function getValidationRules(): array
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

    public function getFields(): array
    {
        return $this->fillable;
    }

    public function getUpdateValidationRules(): array
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
                Rule::unique('customers', 'email_address')->ignore($this->id),
            ],
        ];
    }

    public function getCustomerFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function homeAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'home_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function tShirtSize(): BelongsTo
    {
        return $this->belongsTo(TShirtSize::class, 't_shirt_size_id');
    }

    public function hatSize(): BelongsTo
    {
        return $this->belongsTo(HatSize::class, 'hat_size_id');
    }

    public function orderCustomers(): HasMany
    {
        return $this->hasMany(OrderCustomer::class, 'customer_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, OrderCustomer::class, 'customer_id', 'order_id');
    }

    public function leadingOrders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, OrderCustomer::class, 'customer_id', 'lead_booker_id');
    }

    public function routeNotificationForMail($notification = null): array
    {
        return [$this->email_address => $this->full_name,];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPassword($token));
    }

    public function getEmailForPasswordReset(): ?string
    {
        return $this->email_address;
    }

    public function tokens(): HasMany
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
}
