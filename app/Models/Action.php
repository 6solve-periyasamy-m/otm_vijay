<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Action
 *
 * @property int $id
 * @property string $action
 * @property int $customer_id
 * @property int $order_id
 * @property string|null $reference
 * @property string|null $detail
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Action newModelQuery()
 * @method static Builder|Action newQuery()
 * @method static Builder|Action query()
 * @method static Builder|Action whereAction($value)
 * @method static Builder|Action whereCreatedAt($value)
 * @method static Builder|Action whereCustomerId($value)
 * @method static Builder|Action whereDetail($value)
 * @method static Builder|Action whereId($value)
 * @method static Builder|Action whereOrderId($value)
 * @method static Builder|Action whereReference($value)
 * @method static Builder|Action whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Action extends Model
{
    use HasFactory;

    // actions is a simple log for logging booking form actions and expecting it to evolve quickly
    // there are two controller actions

}
