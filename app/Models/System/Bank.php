<?php

namespace App\Models\System;

use Database\Factories\System\BankFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\System\Bank
 *
 * @property int $id
 * @property string $name
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $town
 * @property string|null $region
 * @property string|null $country
 * @property string|null $postcode
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static BankFactory factory($count = null, $state = [])
 * @method static Builder|Bank newModelQuery()
 * @method static Builder|Bank newQuery()
 * @method static Builder|Bank query()
 * @method static Builder|Bank whereAddressLine1($value)
 * @method static Builder|Bank whereAddressLine2($value)
 * @method static Builder|Bank whereCountry($value)
 * @method static Builder|Bank whereCreatedAt($value)
 * @method static Builder|Bank whereId($value)
 * @method static Builder|Bank whereName($value)
 * @method static Builder|Bank wherePostcode($value)
 * @method static Builder|Bank whereRegion($value)
 * @method static Builder|Bank whereTown($value)
 * @method static Builder|Bank whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Bank extends Model
{
    use HasFactory;
}
