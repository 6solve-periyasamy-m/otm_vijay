<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    public $additional_attributes = ['event_details'];

    function getEventDetailsAttribute() 
    {
        return $this->event_title . ' - ' . Carbon::parse($this->event_start_date)->format('d/m/Y') . ' : ' . Carbon::parse($this->event_end_date)->format('d/m/Y');
    }
}
