<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $additional_attributes = ['event_details'];

    protected $fillable = ['event_title','event_description','event_start_date','event_end_date','booking_url','notes',];

    function getEventDetailsAttribute() 
    {
        return $this->event_title . ' - ' . Carbon::parse($this->event_start_date)->format('d/m/Y') . ' : ' . Carbon::parse($this->event_end_date)->format('d/m/Y');
    }
}
