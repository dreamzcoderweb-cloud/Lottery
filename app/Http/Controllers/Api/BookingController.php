<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SlotItem;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use Carbon\Carbon;


class BookingController extends Controller
{


        public function index()
        {
            $customer = auth()->user();

            if (!$customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // result logic execute
            $resultData = $this->processResults($customer);

            $bookings = Booking::with([
                    'slot:slot_id,main_title,draw_date,draw_time',
                    'slotItem:slot_items_id,slot_id,title,group_name,digit,color,win_amount,ticket_amt',
                ])
                ->where('customer_id', $customer->customer_id)
                ->orderBy('booking_id', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Bookings retrieved successfully',

                // existing response
                'data' => $bookings,

                // separate winner array
                'total_win_amount' => $resultData['total_win_amount'],
                'winners' => $resultData['winners'],
                'expired_slots' => $resultData['expired_slots'],
            ]);
        }
        public function store(Request $request)
        {
            DB::beginTransaction();

            try {

                $validated = $request->validate([

                    'items' => ['required', 'array', 'min:1'],

                    'items.*.slot_id' => [
                        'required',
                        'integer',
                        'exists:slots,slot_id'
                    ],

                    'items.*.slot_item_id' => [
                        'required',
                        'integer',
                        'exists:slot_items,slot_items_id'
                    ],

                    'items.*.title_id' => [
                        'required',
                        'integer'
                    ],

                    'items.*.digits' => [
                        'required',
                        'regex:/^\d+$/'
                    ],

                    'items.*.qty' => [
                        'required',
                        'integer',
                        'min:1'
                    ],

                    'items.*.amount' => [
                        'required',
                        'numeric',
                        'min:1'
                    ],

                ]);



                $customerId = auth()->id();

                // TOTAL BOOKING AMOUNT (server-authoritative aggregate of item amounts)
                $totalAmount = (float) collect($validated['items'])->sum('amount');

                // CUSTOMER WALLET
                $wallet = WalletRecharge::where('customer_id', $customerId)
                    ->lockForUpdate()
                    ->first();

                // CHECK WALLET EXISTS
                if (!$wallet) {

                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Wallet not found'
                    ], 404);
                }

                // CHECK BALANCE
                if ($wallet->balance < $totalAmount) {

                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Insufficient wallet balance'
                    ], 422);
                }

                // DEDUCT WALLET BALANCE
                $wallet->decrement('balance', $totalAmount);

                // CREATE WALLET TRANSACTION (single aggregated debit)
                WalletTransactions::create([
                    'customer_id'    => $customerId,
                    'type'           => 'debit',
                    'amount'         => $totalAmount,
                    'payment_method' => null,
                    'remarks'        => 'Lottery Booking Amount Deducted',
                ]);

                $bookings = [];

                foreach ($validated['items'] as $item) {

                    $slot = Slot::query()
                        ->select(['slot_id', 'draw_date', 'booking_close_time'])
                        ->find($item['slot_id']);

                    if (!$slot) {
                        throw new \RuntimeException('Slot not found for booking.');
                    }

                    $booking = Booking::create([
                        'customer_id'    => $customerId,
                        'slot_id'        => $item['slot_id'],
                        'slot_items_id'  => $item['slot_item_id'],
                        'title_id'       => $item['title_id'],
                        'digits'         => $item['digits'],
                        'qty'            => $item['qty'],
                        'amount'         => (float) $item['amount'],
                        'status'         => 'success',
                        // bookings table columns are time-only, so store just the time portion.
                        'booking_time'   => now()->format('H:i:s'),
                        // Use slot close time so morning/evening bookings are distinguished correctly.
                        'close_time'     => $slot->booking_close_time,
                        'payment_status' => 'paid',
                    ]);

                    $bookings[] = $booking;
                }

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Bookings created successfully',

                    'wallet' => [
                        'deducted_amount' => $totalAmount,
                        'remaining_balance' => $wallet->fresh()->balance,
                    ],

                    'data' => $bookings,

                ], 201);

            } catch (\Illuminate\Validation\ValidationException $e) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ], 422);

            } catch (\Exception $e) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }


    public function result()
    {
        $customer = auth()->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $resultData = $this->processResults(
            $customer,
            request()->query('slot_id')
        );

        return response()->json([
            'status' => true,
            'message' => 'Result checked successfully',
            'current_time' => now('Asia/Kolkata')->format('Y-m-d H:i:s'),

            'total_win_amount' => $resultData['total_win_amount'],
            'winners' => $resultData['winners'],
            'expired_slots' => $resultData['expired_slots'],
        ]);
    }


    private function processResults($customer, $slotId = null)
    {
        $bookingsQuery = Booking::with(['slot.items','slotItem'])->where('customer_id', $customer->customer_id)->where('status', 'success');

        if ($slotId) {
            $bookingsQuery->where('slot_id', $slotId);
        }

        $bookings = $bookingsQuery->get();

        $totalWinAmount = 0;
        $winningBookings = [];
        $expiredSlots = [];
        $expiredSlotIds = [];
        $threeDigitCount = 0;

        $currentDateTime = Carbon::now('Asia/Kolkata');
        $currentDate = $currentDateTime->format('Y-m-d');

        foreach ($bookings as $booking) {

            /*
            |--------------------------------------------------------------------------
            | Slot Exists Check
            |--------------------------------------------------------------------------
            */

            if (!$booking->slot) {
                continue;
            }

            $slot = $booking->slot;

            /*
            |--------------------------------------------------------------------------
            | Future Slot Skip
            |--------------------------------------------------------------------------
            */

            if ($slot->draw_date > $currentDate) {
                continue;
            }



            /*
            |--------------------------------------------------------------------------
            | Close Time Check (Commented Out)
            |--------------------------------------------------------------------------
            */

            /*
            $closeTime = $slot->booking_close_time ?? ($slot->close_time ?? null);

            if (!empty($slot->draw_date) && !empty($closeTime)) {
                $closeTimeString = (string) $closeTime;
                if (preg_match('/^\d{2}:\d{2}$/', $closeTimeString)) {
                    $closeTimeString .= ':00';
                }

                try {
                    $closeDateTime = Carbon::createFromFormat(
                        'Y-m-d H:i:s',
                        $slot->draw_date . ' ' . $closeTimeString,
                        'Asia/Kolkata'
                    );
                } catch (\Throwable $e) {
                    $closeDateTime = Carbon::parse(
                        $slot->draw_date . ' ' . $closeTimeString,
                        'Asia/Kolkata'
                    );
                }

                // If current time is before closing time, slot is still open for booking
                if ($currentDateTime->timestamp < $closeDateTime->timestamp) {
                    continue;
                }
            }
            */

            /*
            |--------------------------------------------------------------------------
            | Digits Null Check (All digits must be non-null)
            |--------------------------------------------------------------------------
            */

            if (is_null($booking->digits) || trim((string) $booking->digits) === '') {
                continue;
            }

            $slotItems = $slot->items;
            if ($slotItems->isEmpty()) {
                continue;
            }

            $hasNullDigits = $slotItems->contains(function ($item) {
                return is_null($item->digit) || trim((string) $item->digit) === '';
            });

            if ($hasNullDigits) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Already Processed
            |--------------------------------------------------------------------------
            */

            if ((int)$booking->title_id === 3) {
                $slotItem = $booking->slotItem ?? SlotItem::find($booking->slot_items_id);
                $threeDigitResult = $this->resolveThreeDigitPrize($booking, $slotItem);

                if ($threeDigitResult['is_winner']) {
                    $expectedWinAmount = $threeDigitResult['win_amount'];
                    $expectedFlags = [
                        'first_price_flag' => $threeDigitResult['first_price_flag'] ? 'true' : null,
                        'second_price_flag' => $threeDigitResult['second_price_flag'] ? 'true' : null,
                        'third_price_flag' => $threeDigitResult['third_price_flag'] ? 'true' : null,
                    ];

                    if ($booking->is_winner !== "true" || (float) ($booking->win_amount ?? 0) !== (float) $expectedWinAmount ||
                        $booking->first_price_flag !== $expectedFlags['first_price_flag'] ||
                        $booking->second_price_flag !== $expectedFlags['second_price_flag'] ||
                        $booking->third_price_flag !== $expectedFlags['third_price_flag']
                    ) {
                        $booking->update(array_merge([
                            'is_winner' => "true",
                            'win_amount' => $expectedWinAmount,
                        ], $expectedFlags));
                    }



                    $totalWinAmount += $expectedWinAmount;

                    $winnerData = [
                        'booking_id'        => $booking->booking_id,
                        'slot_id'           => $booking->slot_id,
                        'slot_items_id'     => $booking->slot_items_id,
                        'title'             => $booking->title_id,
                        'digits'            => $booking->digits,
                        'qty'               => $booking->qty,
                        'single_win_amount' => $threeDigitResult['single_win_amount'],
                        'win_amount'        => $expectedWinAmount,
                        'first_price_flag'  => $threeDigitResult['first_price_flag'],
                        'second_price_flag' => $threeDigitResult['second_price_flag'],
                        'third_price_flag'  => $threeDigitResult['third_price_flag'],
                        'winning_approved'  => (int) ($booking->winning_approved ?? 0),
                    ];

                    $winningBookings[] = $winnerData;
                } else {
                    if ($booking->is_winner !== "false") {
                        $booking->update([
                            'is_winner' => "false",
                            'win_amount' => 0,
                            'first_price_flag' => null,
                            'second_price_flag' => null,
                            'third_price_flag' => null,
                        ]);
                    }
                }

                if ($slot->draw_date < $currentDate && !in_array($slot->slot_id, $expiredSlotIds)) {
                    $expiredSlots[] = [
                        'slot_id'   => $slot->slot_id,
                        'message'   => 'Slot expired',
                        'draw_date' => $slot->draw_date
                    ];
                    $expiredSlotIds[] = $slot->slot_id;
                }

                continue;
            }

            if (!is_null($booking->is_winner)) {

                $isWinnerVal = $booking->is_winner === true || $booking->is_winner === "true" || $booking->is_winner === 1 || $booking->is_winner === "1";

                if ($isWinnerVal) {
                    $singleWinAmount = optional($booking->slotItem)->win_amount;

                    $winnerData = [
                        'booking_id'        => $booking->booking_id,
                        'slot_id'           => $booking->slot_id,
                        'slot_items_id'     => $booking->slot_items_id,
                        'title'             => $booking->title_id,
                        'digits'            => $booking->digits,
                        'qty'               => $booking->qty,
                        'single_win_amount' => $singleWinAmount,
                        'win_amount'        => $booking->win_amount,
                        'winning_approved'  => (int) ($booking->winning_approved ?? 0),
                    ];

                    $winningBookings[] = $winnerData;

                    $totalWinAmount += (float) ($booking->win_amount ?? 0);
                }

                /*
                |--------------------------------------------------------------------------
                | Expired Slot List
                |--------------------------------------------------------------------------
                */

                if ($slot->draw_date < $currentDate && !in_array($slot->slot_id, $expiredSlotIds)) {

                    $expiredSlots[] = [
                        'slot_id'   => $slot->slot_id,
                        'message'   => 'Slot expired',
                        'draw_date' => $slot->draw_date
                    ];

                    $expiredSlotIds[] = $slot->slot_id;
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Winner Check
            |--------------------------------------------------------------------------
            */

            // Non-3-digit title logic:
            $winnerSlotItem = SlotItem::find($booking->slot_items_id);

            if ($winnerSlotItem && $winnerSlotItem->slot_id == $booking->slot_id && $winnerSlotItem->title == $booking->title_id && (string)$winnerSlotItem->digit === (string)$booking->digits) {

                /*
                |--------------------------------------------------------------------------
                | Win Amount Calculation
                |--------------------------------------------------------------------------
                */

                $winAmount = $winnerSlotItem->win_amount * $booking->qty;

                /*
                |--------------------------------------------------------------------------
                | Booking Update
                |--------------------------------------------------------------------------
                */

                $booking->update([
                    'is_winner' => "true",
                    'win_amount' => $winAmount
                ]);



                /*
                |--------------------------------------------------------------------------
                | Response Data
                |--------------------------------------------------------------------------
                */

                $totalWinAmount += $winAmount;

                $winningBookings[] = [
                    'booking_id'        => $booking->booking_id,
                    'slot_id'           => $booking->slot_id,
                    'slot_items_id'     => $booking->slot_items_id,
                    'title'             => $booking->title_id,
                    'digits'            => $booking->digits,
                    'qty'               => $booking->qty,
                    'single_win_amount' => $winnerSlotItem->win_amount,
                    'win_amount'        => $winAmount,
                    'winning_approved'  => (int) ($booking->winning_approved ?? 0),
                ];

            } else {

                /*
                |--------------------------------------------------------------------------
                | Non Winner
                |--------------------------------------------------------------------------
                */

                $booking->update([
                    'is_winner' => "false",
                    'win_amount' => 0
                ]);

                // Do not create a zero-value wallet transaction for non-winning bookings.
            }

            /*
            |--------------------------------------------------------------------------
            | Expired Slot Add
            |--------------------------------------------------------------------------
            */

            if ($slot->draw_date < $currentDate && !in_array($slot->slot_id, $expiredSlotIds)) {

                $expiredSlots[] = [
                    'slot_id'   => $slot->slot_id,
                    'message'   => 'Slot expired',
                    'draw_date' => $slot->draw_date
                ];

                $expiredSlotIds[] = $slot->slot_id;
            }
        }

        return [
            'total_win_amount' => $totalWinAmount,
            'winners' => $winningBookings,
            'expired_slots' => $expiredSlots,
        ];
    }

    private function resolveThreeDigitPrize(Booking $booking, ?SlotItem $slotItem): array
    {
        $result = [
            'is_winner' => false,
            'win_amount' => 0,
            'single_win_amount' => 0,
            'first_price_flag' => false,
            'second_price_flag' => false,
            'third_price_flag' => false,
        ];

        $slotItem = $this->getThreeDigitSlotItem($booking, $slotItem);
        if (!$slotItem) {
            return $result;
        }

        // Do not overwrite the original booking's slot_items_id here.
        // Overwriting can incorrectly attribute a booking to a different
        // slot item (for example the admin-declared winning item)
        // which causes non-winning bookings to become winners.

        $bookingDigitStr = str_pad((string)$booking->digits, 3, '0', STR_PAD_LEFT);
        $winningDigitStr = str_pad((string)$slotItem->digit, 3, '0', STR_PAD_LEFT);

        if ($bookingDigitStr === $winningDigitStr) {
            $result['is_winner'] = true;
            $result['single_win_amount'] = (float) $slotItem->first_price;
            $result['win_amount'] = $result['single_win_amount'] * $booking->qty;
            $result['first_price_flag'] = true;
        } elseif (substr($bookingDigitStr, 1, 2) === substr($winningDigitStr, 1, 2)) {
            $result['is_winner'] = true;
            $result['single_win_amount'] = (float) $slotItem->second_price;
            $result['win_amount'] = $result['single_win_amount'] * $booking->qty;
            $result['second_price_flag'] = true;
        } elseif (substr($bookingDigitStr, 2, 1) === substr($winningDigitStr, 2, 1)) {
            $result['is_winner'] = true;
            $result['single_win_amount'] = (float) $slotItem->third_price;
            $result['win_amount'] = $result['single_win_amount'] * $booking->qty;
            $result['third_price_flag'] = true;
        }

        return $result;
    }

    private function getThreeDigitSlotItem(Booking $booking, ?SlotItem $slotItem): ?SlotItem
    {
        // Calculate per-ticket amount
        $ticketAmount = $booking->qty > 0
            ? ($booking->amount / $booking->qty)
            : $booking->amount;

        // Reuse the existing SlotItem if it already matches
        if (
            $slotItem &&
            (int) $slotItem->slot_id === (int) $booking->slot_id &&
            (int) $slotItem->title === (int) $booking->title_id &&
            (float) $slotItem->ticket_amt === (float) $ticketAmount
        ) {
            return $slotItem;
        }

        // Find the correct SlotItem
        $query = SlotItem::where('slot_id', $booking->slot_id)
            ->where('title', $booking->title_id)
            ->where('ticket_amt', $ticketAmount);

        return $query->first();
    }
}
