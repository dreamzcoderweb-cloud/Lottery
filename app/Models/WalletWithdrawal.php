<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletWithdrawal extends Model
{
    protected $table = 'wallet_withdrawals';
    protected $primaryKey = 'wallet_withdrawal_id';

    protected $fillable = [
        'customer_id',
        'amount',
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
