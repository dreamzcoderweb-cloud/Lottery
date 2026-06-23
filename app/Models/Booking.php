<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'slot_id',
        'slot_items_id',
        'title_id',
        'digits',
        'qty',
        'amount',
        'status',
        'payment_status',
        'booking_time',
        'close_time',
        'is_winner',
        'win_amount',
        'first_price_flag',
        'second_price_flag',
        'third_price_flag',
    ];

    protected $primaryKey = 'booking_id';


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id', 'slot_id');
    }

    public function slotItem()
    {
        return $this->belongsTo(
            SlotItem::class,
            'slot_items_id',
            'slot_items_id'
        );
    }
}
