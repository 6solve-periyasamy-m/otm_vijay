<?php

namespace App\Models\Supplier;

use App\Models\Traits\Fetches;
use Database\Factories\Supplier\SupplierAssociateFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\SupplierAssociate
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $name
 * @property string|null $email
 * @property string|null $primary_phone
 * @property string|null $alternative_phone
 * @property string|null $job_title
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Supplier $supplier
 * @method static SupplierAssociateFactory factory($count = null, $state = [])
 * @method static Builder|SupplierAssociate newModelQuery()
 * @method static Builder|SupplierAssociate newQuery()
 * @method static Builder|SupplierAssociate query()
 * @method static Builder|SupplierAssociate whereAlternativePhone($value)
 * @method static Builder|SupplierAssociate whereCreatedAt($value)
 * @method static Builder|SupplierAssociate whereEmail($value)
 * @method static Builder|SupplierAssociate whereId($value)
 * @method static Builder|SupplierAssociate whereJobTitle($value)
 * @method static Builder|SupplierAssociate whereName($value)
 * @method static Builder|SupplierAssociate whereNotes($value)
 * @method static Builder|SupplierAssociate wherePrimaryPhone($value)
 * @method static Builder|SupplierAssociate whereSupplierId($value)
 * @method static Builder|SupplierAssociate whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SupplierAssociate extends Model
{
    protected $guarded = [];

    use HasFactory, Fetches;

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
