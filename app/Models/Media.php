<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Media
 *
 * @property int $id
 * @property string $file_path
 * @property string $mediable_type
 * @property int $mediable_id
 * @property string $type
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Media newModelQuery()
 * @method static Builder|Media newQuery()
 * @method static Builder|Media query()
 * @method static Builder|Media whereCreatedAt($value)
 * @method static Builder|Media whereId($value)
 * @method static Builder|Media whereType($value)
 * @method static Builder|Media whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Media extends Model
{
    protected $fillable = ['file_path', 'mediable_id', 'mediable_type', 'type', 'description'];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
