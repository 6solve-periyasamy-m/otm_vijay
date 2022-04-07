<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\TicketType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|TicketType newModelQuery()
 * @method static Builder|TicketType newQuery()
 * @method static QueryBuilder|TicketType onlyTrashed()
 * @method static Builder|TicketType query()
 * @method static Builder|TicketType whereCreatedAt($value)
 * @method static Builder|TicketType whereDeletedAt($value)
 * @method static Builder|TicketType whereId($value)
 * @method static Builder|TicketType whereName($value)
 * @method static Builder|TicketType whereUpdatedAt($value)
 * @method static QueryBuilder|TicketType withTrashed()
 * @method static QueryBuilder|TicketType withoutTrashed()
 * @mixin Eloquent
 */
class TicketType extends SimpleModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:ticket_types,name',];
    }
}
