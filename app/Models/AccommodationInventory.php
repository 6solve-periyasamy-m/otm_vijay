<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jahondust\ModelLog\Traits\ModelLogging;
use Carbon\Carbon;


class AccommodationInventory extends Model
{
    use HasFactory, ModelLogging;

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class);
    }

    public function boardType()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function getAccommodationForTourAttribute()
    {
      // dd($this->accommodation->region);
      $check_in_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $this->check_in_date_time)->format('d/m/Y H:i');
      $check_out_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $this->check_out_date_time)->format('d/m/Y H:i');

       return "{$this->accommodation->title} - {$this->accommodation->region->region_name}｜Check in: {$check_in_date_time} - Check out: {$check_out_date_time}｜Room Type: {$this->roomType->room_type_name} - Board Type: {$this->boardType->board_type_name}";
    }
    public $additional_attributes = ['Accommodation_for_tour'];
}
    $logFields = ['accommodation_id','purchase_price'];
