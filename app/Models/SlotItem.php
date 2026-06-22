<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlotItem extends Model
{
     use SoftDeletes;

     protected $primaryKey = 'slot_items_id';

     protected $fillable = [
        'slot_id',
        'title',
        'group_name',
        'digit',
        'color',
        'win_amount',
        'ticket_amt',
        'first_price',
        'second_price',
        'third_price',
    ];

    protected $casts = [
        'win_amount' => 'decimal:2',
        'ticket_amt' => 'decimal:2',
        'first_price' => 'decimal:2',
        'second_price' => 'decimal:2',
        'third_price' => 'decimal:2',
    ];

    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id', 'slot_id');
    }
}
