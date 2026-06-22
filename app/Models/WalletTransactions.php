<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransactions extends Model
{
     protected $table = 'wallet_transactions';
     protected $primaryKey = 'wallet_transaction_id';

     protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'payment_method',
        'reference_no',
        'remarks',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
