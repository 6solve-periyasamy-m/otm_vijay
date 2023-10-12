<?php

namespace App\Models\System;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * \App\Models\System\FellohLink
 *
 * @property int $id
 * @property string $order_type
 * @property int $order_id
 * @property string $felloh_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FellohLink newModelQuery()
 * @method static Builder|FellohLink newQuery()
 * @method static Builder|FellohLink query()
 * @method static Builder|FellohLink whereCreatedAt($value)
 * @method static Builder|FellohLink whereFellohId($value)
 * @method static Builder|FellohLink whereId($value)
 * @method static Builder|FellohLink whereOrderId($value)
 * @method static Builder|FellohLink whereOrderType($value)
 * @method static Builder|FellohLink whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FellohLink extends Model
{
    protected $guarded = [];
}
