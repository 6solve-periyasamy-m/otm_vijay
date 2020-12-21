<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ActivityInventory extends Model
{
    
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }
}