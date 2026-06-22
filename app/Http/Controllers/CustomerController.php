<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
        public function index(){
        $customers = Customer::all();

        return view('customers.view', compact('customers'));
        }

        public function show(int $id)
        {
            $customer = Customer::where('customer_id', $id)->firstOrFail();

            $bankAccounts = BankAccount::query()
                ->where('customer_id', $customer->customer_id)
                ->orderByDesc('bank_account_id')
                ->get();

            $bookings = Booking::query()
                ->with([
                    'slot:slot_id,main_title,short_title,draw_date,booking_close_time,draw_time,status',
                    'slotItem:slot_items_id,slot_id,group_name,digit,color,win_amount,ticket_amt',
                ])
                ->where('customer_id', $customer->customer_id)
                ->orderByDesc('booking_id')
                ->limit(200)
                ->get();

            // Wallet Recharge Details
            $walletRecharges = WalletRecharge::query()
                ->where('customer_id', $customer->customer_id)
                ->orderByDesc('wallet_recharge_id')
                ->get();

            // Wallet Transaction Details
            $walletTransactions = WalletTransactions::query()
                ->where('customer_id', $customer->customer_id)
                ->latest()
                ->get();

            // Wallet Balance
            $walletBalance = (float) $walletRecharges->sum('balance');

            $summary = [
                'tickets_count' => $bookings->count(),
                'total_qty' => (int) $bookings->sum('qty'),
                'total_amount' => (float) $bookings->sum('amount'),
                'winners_count' => (int) $bookings->filter(
                    fn ($b) =>
                    (string) $b->is_winner === 'true' || (int) $b->is_winner === 1
                )->count(),
                'total_win_amount' => (float) $bookings->sum(
                    fn ($b) => (float) ($b->win_amount ?? 0)
                ),

                // Wallet Summary
                'wallet_balance' => $walletBalance,
                'wallet_recharge_total' => (float) $walletRecharges->sum('balance'),
                'wallet_transaction_total' => (float) $walletTransactions->sum('amount'),
            ];

            return view(
                'customers.show',
                compact(
                    'customer',
                    'bankAccounts',
                    'bookings',
                    'walletRecharges',
                    'walletTransactions',
                    'summary'
                )
            );
        }
}
