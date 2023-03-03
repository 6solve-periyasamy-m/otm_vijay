<?php

namespace App\Models\Activity;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Activity\TicketTypeRepository;
use Database\Factories\Activity\TicketTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

/**
 * App\Models\TicketType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|ActivityInventory[] $inventories
 * @property-read TicketTypeRepository $repository
 * @method static TicketTypeFactory factory(...$parameters)
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
    use HasFactory, SoftDeletes, HasRepository;

    protected $fillable = ['name',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('ticket_types', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:ticket_types,name',];
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(ActivityInventory::class, 'ticket_type_id');
    }
}
