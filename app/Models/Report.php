<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Report
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $parent
 * @property array $fields
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Report newModelQuery()
 * @method static Builder|Report newQuery()
 * @method static QueryBuilder|Report onlyTrashed()
 * @method static Builder|Report query()
 * @method static Builder|Report whereCreatedAt($value)
 * @method static Builder|Report whereDeletedAt($value)
 * @method static Builder|Report whereDescription($value)
 * @method static Builder|Report whereFields($value)
 * @method static Builder|Report whereId($value)
 * @method static Builder|Report whereName($value)
 * @method static Builder|Report whereParent($value)
 * @method static Builder|Report whereUpdatedAt($value)
 * @method static QueryBuilder|Report withTrashed()
 * @method static QueryBuilder|Report withoutTrashed()
 * @mixin Eloquent
 */
class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = ['fields' => 'array',];
    protected $fillable = ['name', 'description', 'parent', 'fields'];

}
