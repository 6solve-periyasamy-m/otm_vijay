<?php

namespace App\Models\Customer;

use App\Models\Helper\NotificationSubject;
use App\Models\Helper\Traits\HasNotifications;
use App\Models\Helper\Traits\MountsLivewire;
use App\Models\Location\Address;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Quote\QuoteProspect;
use App\Models\System\ApiToken;
use App\Models\System\CustomerApiToken;
use App\Models\Traits\HasRepository;
use App\Notifications\CustomerResetPassword;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\Model\Customer\CustomerRepository;
use Database\Factories\Customer\CustomerFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Exception;
use Gravatar;
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
use App\Models\User;


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
 * @property Carbon|null $date_of_birth
 * @property string|null $mobile_number
 * @property string|null $other_phone_number
 * @property string|null $nationality
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
 * @property int|null $organization_id
 * @property int|null $consultant_id
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
 * @property-read bool $registered Is the customer a registered user
 * @property-read Address $homeAddress Home address. Should be a unique entry in the database
 * @property-read Collection|Order[] $leadingOrders Orders where they are the lead booker
 * @property-read Organization|null $organization
 * @property-read CustomerRepository $repository
 * @property-read string $loyalty_numbers_for_report A list of loyalty numbers, ordered for the report
 * @property-read int|null $leading_orders_count Amount of orders where they are the lead booker
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications System notifications for customer
 * @property-read int|null $notifications_count Amount of system notifications for customer
 * @property-read Collection|OrderCustomer[] $orderCustomers OrderCustomers for this customer
 * @property-read Collection|QuoteProspect[] $quoteProspects
 * @property-read Collection|LoyaltyNumber[] $loyaltyNumbers
 * @property-read Collection|CustomerMerchandise[] $customerMerchandises
 * @property-read int|null $order_customers_count Amount of OrderCustomers for this customer
 * @property-read Collection|Order[] $orders Orders for this customer
 * @property-read int|null $orders_count Amount of orders for this customer
 * @property-read Collection|Subscription[] $subscriptions Stripe Subscriptions
 * @property-read int|null $subscriptions_count Amount of Stripe Subscriptions
 * @property-read TShirtSize|null $tShirtSize Customer t-shirt size
 * @property-read Collection|CustomerApiToken[] $tokens Customer API tokens
 * @property-read int|null $tokens_count Amount of Customer API tokens
 * @property-read string $avatar_url The URL for the avatar
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
 * @method static Builder|Customer whereOrganizationId($value)
 * @method static Builder|Customer whereId($value)
 * @method static Builder|Customer whereInternalNotes($value)
 * @method static Builder|Customer whereLastName($value)
 * @method static Builder|Customer whereLoginToken($value)
 * @method static Builder|Customer whereLoyaltyNumber($value)
 * @method static Builder|Customer whereCustomerMerchandise($value)
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
class Customer extends Authenticatable implements NotificationSubject
{
    use SoftDeletes;
    use HasFactory;
    use Notifiable;
    use Billable;
    use CascadeSoftDeletes;
    use HasRepository;
    use HasNotifications;
    use MountsLivewire;

    protected string $guard = 'customer';

    protected $guarded = [];

    protected $casts = ['date_of_birth' => 'date:Y-m-d', 'passport_issue_date' => 'date:Y-m-d', 'passport_expiry_date' => 'date:Y-m-d',];

    protected $hidden = ['password', 'pm_type', 'pm_last_four', 'trial_ends_at'];
    protected array $cascadeDeletes = ['quoteProspects',];
    protected $with = ['loyaltyNumbers', 'customerMerchandises'];

    public static function getValidationRules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'nullable|date',
            'email_address' => 'nullable|email|unique:customers,email_address',
            'consultant_id' => 'nullable|exists:users,id',
        ];
    }

    public function getFields(): array
    {
        return $this->fillable;
    }

    public function getUpdateValidationRules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'nullable|date',
            'email_address' => [
                'nullable',
                'email',
                Rule::unique('customers', 'email_address')->ignore($this->id),
            ],
            'consultant_id' => 'nullable|exists:users,id',
        ];
    }

    public function quoteProspects(): HasMany
    {
        return $this->hasMany(QuoteProspect::class, 'customer_id');
    }

    public function getCustomerFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function loyaltyNumbers(): HasMany
    {
        return $this->hasMany(LoyaltyNumber::class, 'customer_id');
    }

    public function customerMerchandises(): HasMany
    {
        return $this->hasMany(CustomerMerchandise::class, 'customer_id');
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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
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

    public function invalidateAllTokens(): void
    {
        CustomerAuthenticationRepository::invalidateAllUserTokens($this);
    }

    public function purgeTokens(int $limit = ApiToken::DEFAULT_LIMIT): void
    {
        CustomerAuthenticationRepository::purgeUserTokens($this, $limit);
    }

    public function getAvatarUrlAttribute(): string
    {
        if (isset($this->profile_picture)) return asset($this->profile_picture);
        try {
            return isset($this->email_address) ? Gravatar::get($this->email_address) : ('https://secure.gravatar.com/avatar/?d=mp&s=300');
        } catch (Exception $exception) {
            return 'https://secure.gravatar.com/avatar/?d=mp&s=300';
        }
    }

    public function getRegisteredAttribute(): bool
    {
        return isset($this->email_address, $this->password);
    }

    public function getLink(): string
    {
        $route = route('customers.view', ['customer' => $this,]);
        return "<a href='$route'>{$this->full_name}</a>";
    }

    public function getLoyaltyNumbersForReportAttribute(): string
    {
        $str = "";
        foreach ($this->loyaltyNumbers as $loyaltyNumber) {
            $str .= "{$loyaltyNumber->type->name} - {$loyaltyNumber->notes} - {$loyaltyNumber->loyalty_number}\n";
        }
        return $str;
    }
}
