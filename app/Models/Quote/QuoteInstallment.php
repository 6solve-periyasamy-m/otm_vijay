<?php

namespace App\Models\Quote;

use App\Models\Helper\Model;
use Database\Factories\Quote\QuoteInstallmentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuoteInstallment
 *
 * @property int $id
 * @property int $quote_id
 * @property Carbon $due_on
 * @property float $amount
 * @property bool $percentage
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
 * @method static QuoteInstallmentFactory factory(...$parameters)
 * @method static Builder|QuoteInstallment newModelQuery()
 * @method static Builder|QuoteInstallment newQuery()
 * @method static QueryBuilder|QuoteInstallment onlyTrashed()
 * @method static Builder|QuoteInstallment query()
 * @method static Builder|QuoteInstallment whereAmount($value)
 * @method static Builder|QuoteInstallment whereCreatedAt($value)
 * @method static Builder|QuoteInstallment whereDeletedAt($value)
 * @method static Builder|QuoteInstallment whereDueOn($value)
 * @method static Builder|QuoteInstallment whereId($value)
 * @method static Builder|QuoteInstallment wherePercentage($value)
 * @method static Builder|QuoteInstallment whereQuoteId($value)
 * @method static Builder|QuoteInstallment whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteInstallment withTrashed()
 * @method static QueryBuilder|QuoteInstallment withoutTrashed()
 * @mixin Eloquent
 */
class QuoteInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'due_on' => 'date:Y-m-d',
        'amount' => 'double',
        'percentage' => 'boolean'
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function getAmount(int $count = 1, float|null $price = null): float|null
    {
        $price = $price ?? $this->quote->repository->getPricePerPerson($count)?->price_per_person;
        return $this->percentage ? sigfig($price * ($this->amount/100)) : $this->amount;
    }

    public function getPercentage(int $count = 1, float|null $price = null): float|null
    {
        $price = $price ?? $this->quote->repository->getPricePerPerson($count)?->price_per_person;
        if (empty($price)) { return 100; }
        return $this->percentage ? $this->amount : sigfig(($this->amount / $price) * 100);
    }
}
