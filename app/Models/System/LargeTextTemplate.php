<?php

namespace App\Models\System;

use App\Models\Helper\Enum\LargeTextType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * \App\Models\System\LargeTextTemplate
 *
 * @property int $id
 * @property string $name
 * @property LargeTextType $type
 * @property string|null $description
 * @property string|null $content
 * @property bool $default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|LargeTextTemplate newModelQuery()
 * @method static Builder|LargeTextTemplate newQuery()
 * @method static Builder|LargeTextTemplate query()
 * @method static Builder|LargeTextTemplate whereContent($value)
 * @method static Builder|LargeTextTemplate whereCreatedAt($value)
 * @method static Builder|LargeTextTemplate whereDescription($value)
 * @method static Builder|LargeTextTemplate whereId($value)
 * @method static Builder|LargeTextTemplate whereName($value)
 * @method static Builder|LargeTextTemplate whereType($value)
 * @method static Builder|LargeTextTemplate whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LargeTextTemplate extends Model
{
    protected $guarded = [];
    protected $casts = ['type' => LargeTextType::class, 'default' => 'boolean'];
}
