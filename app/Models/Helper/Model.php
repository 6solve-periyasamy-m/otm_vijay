<?php

namespace App\Models\Helper;

use App\Models\Helper\Traits\MountsLivewire;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use MountsLivewire;

    protected $fillable = ['archived',];
}