<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ActivityInventory extends Model
{

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function getActivityForTourAttribute()
    {
        $activity_start_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $this->activity_start_date_time)->format('d/m/Y H:i');
        $activity_end_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $this->activity_end_date_time)->format('d/m/Y H:i');

        return "{$this->activity->title}｜Activity Start: {$activity_start_date_time}｜Activity End: {$activity_end_date_time}｜Ticket Type: {$this->ticketType->ticket_type_name}";
    }
    public $additional_attributes = ['Activity_for_tour'];
}
