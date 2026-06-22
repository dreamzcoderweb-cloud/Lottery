<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletRecharge extends Model
{
    protected $fillable = [
        'customer_id',
        'balance',
        'bank_acc_id',
    ];
    protected $primaryKey = 'wallet_recharge_id';
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
