<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiToken extends Model
{
    use HasFactory, SoftDeletes;

    public const DEFAULT_EXPIRY = 90;
    public const DEFAULT_LIMIT = 48;
    public $incrementing = false;
    protected $fillable = ['token', 'expiry'];
    protected $casts = ['expiry' => 'datetime'];
    protected $primaryKey = 'token';
    protected $keyType = 'string';

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
