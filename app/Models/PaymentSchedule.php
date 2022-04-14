<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\PaymentSchedule
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string $deposit_type
 * @property float|null $deposit
 * @property string $installment_type
 * @property string $installment_period
 * @property float|null $installment
 * @method static Builder|PaymentSchedule newModelQuery()
 * @method static Builder|PaymentSchedule newQuery()
 * @method static Builder|PaymentSchedule query()
 * @method static Builder|PaymentSchedule whereCreatedAt($value)
 * @method static Builder|PaymentSchedule whereDeposit($value)
 * @method static Builder|PaymentSchedule whereDepositType($value)
 * @method static Builder|PaymentSchedule whereId($value)
 * @method static Builder|PaymentSchedule whereInstallment($value)
 * @method static Builder|PaymentSchedule whereInstallmentPeriod($value)
 * @method static Builder|PaymentSchedule whereInstallmentType($value)
 * @method static Builder|PaymentSchedule whereName($value)
 * @method static Builder|PaymentSchedule whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentSchedule extends Model
{
    use HasFactory;
}
