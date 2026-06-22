<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'slot_id';

   protected $fillable = [
        'main_title',
        'draw_date',
        'booking_close_time',
        'draw_time',
        'short_title',
        'title',
        'slug',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(SlotItem::class, 'slot_id', 'slot_id');
    }
    public function bookings()
    {
     return $this->hasMany(Booking::class, 'slot_id', 'slot_id');
    }
}
