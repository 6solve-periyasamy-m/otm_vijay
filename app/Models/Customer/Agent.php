<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User
 *
 * This class represents a User model in the application.
 * It is responsible for handling user-related data and operations.
 *
 * @package App\Models
 * @property int $id The unique identifier for the user.
 * @property string $name The name of the user.
 * @property string $email The email address of the user.
 * @property string $password The hashed password of the user.
 * @property \Carbon\Carbon $created_at The timestamp when the user was created.
 * @property \Carbon\Carbon $updated_at The timestamp when the user was last updated.
 */

class Agent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
