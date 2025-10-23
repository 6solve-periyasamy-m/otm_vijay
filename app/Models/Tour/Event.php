<?php

namespace App\Models\Tour;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Helper\Enum\EventType;
use App\Models\Helper\Model;
use App\Models\Order\Order;
use App\Models\System\Brand;
use App\Models\System\TaxBracket;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Tour\EventRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Settings;

/**
 * App\Models\Tour\Event
 *
 * @property int $id
 * @property string $name
 * @property int|null $brand_id
 * @property int|null $tax_bracket_id
 * @property int|null $parent_event_id
 * @property string|null $description
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property EventType $event_category
 * @property string|null $image_url Asset link for image
 * @property string|null $banner_url Asset link for banner
 * @property string|null $booking_url
 * @property string|null $itinerary_email_subject
 * @property string|null $itinerary_email_template
 * @property string|null $notes
 * @property string|null $onsite_name
 * @property string|null $onsite_email
 * @property string|null $onsite_phone
 * @property string|null $final_terms
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $event_details
 * @property-read Collection|Tour[] $tours
 * @property-read Collection|Order[] $orders
 * @property-read Event $parent
 * @property-read Collection<Event>|Event[] $children
 * @property-read int|null $tours_count
 * @property-read EventRepository $repository
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static QueryBuilder|Event onlyTrashed()
 * @method static Builder|Event query()
 * @method static Builder|Event whereBookingUrl($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDeletedAt($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereEndsAt($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereName($value)
 * @method static Builder|Event whereNotes($value)
 * @method static Builder|Event whereStartsAt($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static QueryBuilder|Event withTrashed()
 * @method static QueryBuilder|Event withoutTrashed()
 * @mixin Eloquent
 */
class Event extends Model
{
    use HasFactory, SoftDeletes, HasRepository;

    protected $guarded = [];
    protected $casts = ['starts_at' => 'date:Y-m-d', 'ends_at' => 'date:Y-m-d', 'event_category' => EventType::class,];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_event_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_event_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'event_id');
    }

    public function activityInventories(): HasManyThrough
    {
        return $this->hasManyThrough(
            ActivityInventory::class,
            Activity::class,
            'event_id',
            'activity_id',
            'id',
            'id'
        );
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function bracket(): BelongsTo
    {
        return $this->belongsTo(TaxBracket::class, 'tax_bracket_id');
    }

    public function taxBracket(): TaxBracket
    {
        if ($this->bracket instanceof TaxBracket) {
            return $this->bracket;
        }
        if (is_array($this->bracket)) {
            return new TaxBracket($this->bracket);
        }
        return Settings::getTaxBracket();
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'event_id');
    }

    /**
     * @param bool $hideNoCategory
     * @return Collection<Tour>
     */
    public function getTours(bool $hideNoCategory = false): Collection
    {
        if ($hideNoCategory) {
            return $this->tours()->whereNotNull('tour_category_id')->get();
        }
        return $this->tours;
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Tour::class, 'event_id', 'tour_id');
    }

    public function __toString()
    {
        return $this->repository->__toString();
    }
}
