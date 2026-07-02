<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use App\Models\WalletWithdrawal;
use App\Models\WalletRechargeRequest;
class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'mobile',
        'password',
        'reference_code',
        'referred_by_customer_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            // Keep explicit; we hash on write in controller.
        ];
    }
    public function wallet()
    {
        return $this->hasOne(WalletRecharge::class, 'customer_id', 'customer_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransactions::class, 'customer_id', 'customer_id');
    }

    public function walletWithdrawals()
    {
        return $this->hasMany(WalletWithdrawal::class, 'customer_id', 'customer_id');
    }

    public function walletRechargeRequests()
    {
        return $this->hasMany(WalletRechargeRequest::class, 'customer_id', 'customer_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(self::class, 'referred_by_customer_id', 'customer_id');
    }

    public function referrals()
    {
        return $this->hasMany(self::class, 'referred_by_customer_id', 'customer_id');
    }
    public function slotitems()
    {
        return $this->hasMany(SlotItem::class, 'slot_items_id ', 'slot_items_id ');
    }
}
