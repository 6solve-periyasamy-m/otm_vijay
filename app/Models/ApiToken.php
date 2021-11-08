<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'expiry'];

    protected $primaryKey = 'token';
    public $incrementing = false;
    protected $keyType = 'string';

    public const DEFAULT_EXPIRY = 90;
    public const DEFAULT_LIMIT = 48;

    public function hasExpired()
    {
        return now()->isAfter($this->expiry);
    }

    public function invalidate()
    {
        $this->expiry = now()->addMinutes(-1);
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
