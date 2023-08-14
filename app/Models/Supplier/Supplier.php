<?php

namespace App\Models\Supplier;

use App\Models\Location\Address;
use App\Models\Location\Currency;
use Database\Factories\Supplier\SupplierFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\Supplier
 *
 * @property int $id
 * @property string $name
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $website
 * @property int $address_id
 * @property int|null $currency_id
 * @property string|null $agreed_exchange
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Currency|null $currency Which currency does the supplier trade in
 * @property-read Address|null $address What is the address of the supplier
 * @method static SupplierFactory factory($count = null, $state = [])
 * @method static Builder|Supplier newModelQuery()
 * @method static Builder|Supplier newQuery()
 * @method static Builder|Supplier query()
 * @method static Builder|Supplier whereAgreedExchange($value)
 * @method static Builder|Supplier whereCreatedAt($value)
 * @method static Builder|Supplier whereCurrencyId($value)
 * @method static Builder|Supplier whereAddressId($value)
 * @method static Builder|Supplier whereEmail($value)
 * @method static Builder|Supplier whereId($value)
 * @method static Builder|Supplier whereName($value)
 * @method static Builder|Supplier whereNotes($value)
 * @method static Builder|Supplier whereTelephone($value)
 * @method static Builder|Supplier whereUpdatedAt($value)
 * @method static Builder|Supplier whereWebsite($value)
 * @mixin Eloquent
 */
class Supplier extends Model
{
    use HasFactory;

    public $with = ['currency',];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
