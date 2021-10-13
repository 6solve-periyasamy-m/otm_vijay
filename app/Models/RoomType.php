<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RoomType extends Model
{
    use SoftDeletes;

    protected $fillable = ['room_type_name','maximum_occupancy',];
}
