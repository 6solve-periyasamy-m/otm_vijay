<?php

namespace App\Models\System;

use App\Models\Location\Address;
use App\Models\Location\Country;
use App\Models\Traits\HasRepository;
use App\Repository\Model\System\BrandRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Settings;

/**
 * App\Models\System\Brand
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $url
 * @property string|null $logo
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $instagram
 * @property int|null $address_id
 * @property int|null $tax_bracket_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address|null $address
 * @property-read Address $active_address
 * @property-read BrandRepository $repository
 * @property-read string $image
 * @property-read string $image_path The raw path to the image on the system
 * @method static Builder|Brand newModelQuery()
 * @method static Builder|Brand newQuery()
 * @method static Builder|Brand query()
 * @method static Builder|Brand whereAddressId($value)
 * @method static Builder|Brand whereCreatedAt($value)
 * @method static Builder|Brand whereEmail($value)
 * @method static Builder|Brand whereFacebook($value)
 * @method static Builder|Brand whereId($value)
 * @method static Builder|Brand whereInstagram($value)
 * @method static Builder|Brand whereLogo($value)
 * @method static Builder|Brand whereName($value)
 * @method static Builder|Brand wherePhone($value)
 * @method static Builder|Brand whereTwitter($value)
 * @method static Builder|Brand whereUpdatedAt($value)
 * @method static Builder|Brand whereUrl($value)
 * @mixin Eloquent
 */
class Brand extends Model
{
    protected $guarded = [];
    use HasRepository;

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function bracket(): BelongsTo
    {
        return $this->belongsTo(TaxBracket::class, 'tax_bracket_id');
    }

    public function taxBracket(): TaxBracket
    {
        return $this->bracket ?? Settings::getTaxBracket();
    }

    public function getActiveAddressAttribute(): Address
    {
        return $this->address ?? self::getSystemAddress();
    }

    public function getImageAttribute(): string
    {
        return asset($this->image_path);
    }

    public function getImagePathAttribute(): string
    {
        return $this->logo ?? setting('company.logo', '');
    }

    public static function getSystemAddress(): Address
    {
        return new Address([
            'name' => 'System Address',
            'address_line_1' => setting('company.address.line_1', ''),
            'address_line_2' => setting('company.address.line_2', ''),
            'town' => setting('company.address.city', ''),
            'region' => setting('company.address.region', ''),
            'country_id' => Country::where('name', 'like', setting('company.address.country', ''))->first()?->id,
            'postcode' => setting('company.address.postcode', ''),
        ]);
    }

    public static function getSystemBrand(): Brand
    {
        $brand = new Brand([
            'name' => setting('company.name', ''),
            'email' => setting('company.contact.email', ''),
            'phone' => setting('company.contact.phone', ''),
            'logo' => setting('company.logo'),
            'url' => setting('company.url', ''),
            'facebook' => setting('social.facebook', ''),
            'twitter' => setting('social.twitter', ''),
            'instagram' => setting('social.instagram', ''),
            'tax_bracket_id' => setting('system.tax.bracket', null),
        ]);
        $brand->setRelation('address', self::getSystemAddress());
        return $brand;
    }
}
