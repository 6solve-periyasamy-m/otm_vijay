<?php

namespace App\Models\Tour;

use App\Models\Order\Order;
use App\Models\System\TaxBracket;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property int|null $tax_bracket_id
 * @property string|null $description
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property string|null $image_url Asset link for image
 * @property string|null $booking_url
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $event_details
 * @property-read Collection|Tour[] $tours
 * @property-read Collection|Order[] $orders
 * @property-read int|null $tours_count
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
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = ['starts_at' => 'date', 'ends_at' => 'date'];

    public static function getValidationRules(): array
    {
        return [
            'name' => 'required',
            'image' => 'nullable|image',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
        ];
    }

    function getEventDetailsAttribute(): string
    {
        return $this->name . ' - ' . Carbon::parse($this->starts_at)->format('d/m/Y') . ' : ' . Carbon::parse($this->ends_at)->format('d/m/Y');
    }

    public function bracket(): BelongsTo
    {
        return $this->belongsTo(TaxBracket::class, 'tax_bracket_id');
    }

    public function taxBracket(): TaxBracket
    {
        return $this->bracket ?? Settings::getTaxBracket();
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'event_id');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Tour::class, 'event_id', 'tour_id');
    }
}
