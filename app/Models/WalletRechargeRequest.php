<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletRechargeRequest extends Model
{
    protected $table = 'wallet_recharge_requests';
    protected $primaryKey = 'wallet_recharge_request_id';

    protected $fillable = [
        'customer_id',
        'amount',
        'payment_method',
        'payment_proof',
        'status',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
