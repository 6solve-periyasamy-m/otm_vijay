<?php

namespace App\Models\Tour;

use App\Models\Tour\Event;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Helper\Enum\EventContentType;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Tour\EventContent
 *
 * @property int $id
 * @property int $event_id
 * @property EventContentType $type
 * @property string $icon
 * @property string $question
 * @property string|null $answer
 * @property bool $active
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Eloquent
 */
class EventContent extends Model
{
    protected $appends = ['icon_url'];
    protected $fillable = [
        'event_id',
        'type',
        'icon',
        'question',
        'answer',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'type'   => EventContentType::class,
        'active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getIconUrlAttribute(): ?string
    {
        if (! $this->icon) {
            return null;
        }
        if (str_starts_with($this->icon, 'http')) {
            return $this->icon;
        }
        return asset($this->icon);
    }
}