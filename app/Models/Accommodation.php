<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jahondust\ModelLog\Traits\ModelLogging;


class Accommodation extends Model
{
    use ModelLogging;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
