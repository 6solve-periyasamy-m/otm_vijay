<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketType extends SimpleModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules()
    {
        return ['name' => 'required|unique:ticket_types,name',];
    }

    public function __toString()
    {
        return $this->name;
    }
}
