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

    // public function index()
    // {
    //     $customer = auth()->user();

    //     if (!$customer) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Unauthorized'
    //         ], 401);
    //     }

    //     $bookings = Booking::with([
    //             'slot:slot_id,main_title,draw_date,draw_time',
    //             'slotItem:slot_items_id,slot_id,title,group_name,digit,color,win_amount,ticket_amt',
    //         ])
    //         ->where('customer_id', $customer->customer_id)
    //         ->orderBy('booking_id', 'desc')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Bookings retrieved successfully',
    //         'data' => $bookings,
    //     ]);
    // }
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
                        'integer'
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

                // TOTAL BOOKING AMOUNT
                $totalAmount = collect($validated['items'])
                ->sum(function ($item) {
                    return $item['amount'] * $item['qty'];
                });

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

                // CREATE WALLET TRANSACTION
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
                        'amount'         => $item['amount'],
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

    // public function result()
    // {
    //     $customer = auth()->user();

    //     if (!$customer) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Unauthorized'
    //         ], 401);
    //     }

    //     $bookingsQuery = Booking::with('slot')
    //         ->where('customer_id', $customer->customer_id)
    //         ->where('status', 'success');

    //     if (request()->filled('slot_id')) {
    //         $bookingsQuery->where('slot_id', (int) request()->query('slot_id'));
    //     }

    //     $bookings = $bookingsQuery->get();

    //     if ($bookings->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No bookings found'
    //         ], 404);
    //     }

    //     $totalWinAmount = 0;
    //     $winningBookings = [];
    //     $expiredSlots = [];

    //     // duplicate expired slot avoid
    //     $expiredSlotIds = [];

    //     $currentDateTime = Carbon::now('Asia/Kolkata');
    //     $currentDate = $currentDateTime->format('Y-m-d');

    //     foreach ($bookings as $booking) {

    //         if (!$booking->slot) {
    //             continue;
    //         }

    //         $slot = $booking->slot;

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 1. Expired Slot Check
    //         |--------------------------------------------------------------------------
    //         | draw_date < current_date
    //         */

    //         if ($slot->draw_date < $currentDate) {

    //             // duplicate avoid
    //             if (!in_array($slot->slot_id, $expiredSlotIds)) {

    //                 $expiredSlots[] = [
    //                     'slot_id' => $slot->slot_id,
    //                     'message' => 'Slot expired',
    //                     'draw_date' => $slot->draw_date
    //                 ];

    //                 $expiredSlotIds[] = $slot->slot_id;
    //             }

    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 2. Future Slot Skip
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($slot->draw_date > $currentDate) {
    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 3. Current Date Slot Time Check
    //         |--------------------------------------------------------------------------
    //         */

    //         $slotDateTime = Carbon::parse(
    //             $slot->draw_date . ' ' . $slot->booking_close_time,
    //             'Asia/Kolkata'
    //         );

    //         // before close time
    //         if ($currentDateTime->lt($slotDateTime)) {
    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Already Winner Processed
    //         |--------------------------------------------------------------------------
    //          */

    //         // If already processed (winner OR non-winner), skip.
    //         if (!is_null($booking->is_winner)) {
    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Winner Check
    //         |--------------------------------------------------------------------------
    //         */

    //         $winnerSlotItem = SlotItem::where('slot_id', $booking->slot_id)
    //             ->where('title', $booking->title_id)
    //             ->where('digit', $booking->digits)
    //             ->first();

    //         if ($winnerSlotItem) {

    //              // qty based total win amount
    //             $winAmount = $winnerSlotItem->win_amount * $booking->qty;

    //             // booking update
    //             $booking->update([
    //                 'is_winner' => "true",
    //                 'win_amount' => $winAmount
    //             ]);

    //             // wallet update
    //             $wallet = WalletRecharge::firstOrCreate(
    //                 ['customer_id' => $customer->customer_id],
    //                 ['balance' => 0]
    //             );

    //             $wallet->increment('balance', $winAmount);

    //             $totalWinAmount += $winAmount;

    //             $winningBookings[] = [
    //                 'booking_id' => $booking->booking_id,
    //                 'slot_id' => $booking->slot_id,
    //                 'title' => $booking->title_id,
    //                 'digits' => $booking->digits,
    //                 'qty' => $booking->qty,
    //                 'single_win_amount' => $winnerSlotItem->win_amount,
    //                 'win_amount' => $winAmount
    //             ];
    //         }else{
    //             // booking update for non winner
    //             $booking->update([
    //                 'is_winner' => "false",
    //                 'win_amount' => 0
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Result checked successfully',
    //         'current_time' => $currentDateTime->format('Y-m-d H:i:s'),
    //         'total_win_amount' => $totalWinAmount,
    //         'winners' => $winningBookings,
    //         'expired_slots' => $expiredSlots,
    //     ]);
    // }
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

    // private function processResults($customer, $slotId = null)
    // {
    //     $bookingsQuery = Booking::with([
    //             'slot',
    //             'slotItem'
    //         ])
    //         ->where('customer_id', $customer->customer_id)
    //         ->where('status', 'success');

    //     if ($slotId) {
    //         $bookingsQuery->where('slot_id', $slotId);
    //     }

    //     $bookings = $bookingsQuery->get();

    //     $totalWinAmount = 0;
    //     $winningBookings = [];
    //     $expiredSlots = [];
    //     $expiredSlotIds = [];

    //     $currentDateTime = Carbon::now('Asia/Kolkata');
    //     $currentDate = $currentDateTime->format('Y-m-d');

    //     foreach ($bookings as $booking) {

    //         if (!$booking->slot) {
    //             continue;
    //         }

    //         $slot = $booking->slot;

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 1. Expired Slot Check
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($slot->draw_date < $currentDate) {

    //             if (!in_array($slot->slot_id, $expiredSlotIds)) {

    //                 $expiredSlots[] = [
    //                     'slot_id' => $slot->slot_id,
    //                     'message' => 'Slot expired',
    //                     'draw_date' => $slot->draw_date
    //                 ];

    //                 $expiredSlotIds[] = $slot->slot_id;
    //             }

    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 2. Future Slot Skip
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($slot->draw_date > $currentDate) {
    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 3. Current Date Close Time Check
    //         |--------------------------------------------------------------------------
    //         */

    //         $closeTime = $slot->booking_close_time ?? ($slot->close_time ?? null);
    //         if (empty($slot->draw_date) || empty($closeTime)) {
    //             continue;
    //         }

    //         $closeTimeString = (string) $closeTime;
    //         if (preg_match('/^\d{2}:\d{2}$/', $closeTimeString)) {
    //             $closeTimeString .= ':00';
    //         }

    //         try {
    //             $closeDateTime = Carbon::createFromFormat(
    //                 'Y-m-d H:i:s',
    //                 $slot->draw_date . ' ' . $closeTimeString,
    //                 'Asia/Kolkata'
    //             );
    //         } catch (\Throwable $e) {
    //             $closeDateTime = Carbon::parse(
    //                 $slot->draw_date . ' ' . $closeTimeString,
    //                 'Asia/Kolkata'
    //             );
    //         }

    //         // close time not completed
    //         if ($currentDateTime->timestamp < $closeDateTime->timestamp) {

    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Already Processed
    //         |--------------------------------------------------------------------------
    //         */

    //         if (!is_null($booking->is_winner)) {

    //             if ($booking->is_winner == "true") {

    //                 $winningBookings[] = [
    //                     'booking_id'        => $booking->booking_id,
    //                     'slot_id'           => $booking->slot_id,
    //                     'slot_items_id'     => $booking->slot_items_id,
    //                     'title'             => $booking->title_id,
    //                     'digits'            => $booking->digits,
    //                     'qty'               => $booking->qty,
    //                     'single_win_amount' => optional($booking->slotItem)->win_amount,
    //                     'win_amount'        => $booking->win_amount
    //                 ];

    //                 $totalWinAmount += $booking->win_amount;
    //             }

    //             continue;
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Winner Check
    //         |--------------------------------------------------------------------------
    //         | Exact slot_items_id match only
    //         */

    //         $winnerSlotItem = SlotItem::find($booking->slot_items_id);

    //         if (
    //             $winnerSlotItem &&
    //             $winnerSlotItem->slot_id == $booking->slot_id &&
    //             $winnerSlotItem->title == $booking->title_id &&
    //             $winnerSlotItem->digit == $booking->digits
    //         ) {

    //             // qty based total win amount
    //             $winAmount = $winnerSlotItem->win_amount * $booking->qty;

    //             // booking update
    //             $booking->update([
    //                 'is_winner' => "true",
    //                 'win_amount' => $winAmount
    //             ]);

    //             // wallet update
    //             $wallet = WalletRecharge::firstOrCreate(
    //                 ['customer_id' => $customer->customer_id],
    //                 ['balance' => 0]
    //             );

    //             $wallet->increment('balance', $winAmount);

    //             $totalWinAmount += $winAmount;

    //             $winningBookings[] = [
    //                 'booking_id'        => $booking->booking_id,
    //                 'slot_id'           => $booking->slot_id,
    //                 'slot_items_id'     => $booking->slot_items_id,
    //                 'title'             => $booking->title_id,
    //                 'digits'            => $booking->digits,
    //                 'qty'               => $booking->qty,
    //                 'single_win_amount' => $winnerSlotItem->win_amount,
    //                 'win_amount'        => $winAmount
    //             ];

    //         } else {

    //             // non winner
    //             $booking->update([
    //                 'is_winner' => "false",
    //                 'win_amount' => 0
    //             ]);
    //         }
    //     }

    //     return [
    //         'total_win_amount' => $totalWinAmount,
    //         'winners' => $winningBookings,
    //         'expired_slots' => $expiredSlots,
    //     ];
    // }
    private function processResults($customer, $slotId = null)
{
    $bookingsQuery = Booking::with([
            'slot',
            'slotItem'
        ])
        ->where('customer_id', $customer->customer_id)
        ->where('status', 'success');

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
        | Close Time Check
        |--------------------------------------------------------------------------
        */

        $closeTime = $slot->booking_close_time ?? ($slot->close_time ?? null);

        if (empty($slot->draw_date) || empty($closeTime)) {
            continue;
        }

        $closeTimeString = (string) $closeTime;

        // HH:MM → HH:MM:SS
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

        /*
        |--------------------------------------------------------------------------
        | Result Not Ready Yet
        |--------------------------------------------------------------------------
        */

        if ($currentDateTime->timestamp < $closeDateTime->timestamp) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Already Processed
        |--------------------------------------------------------------------------
        */

        if (!is_null($booking->is_winner)) {

            $isWinnerVal = $booking->is_winner === true || $booking->is_winner === "true" || $booking->is_winner === 1 || $booking->is_winner === "1";

            if ($isWinnerVal) {

                $singleWinAmount = optional($booking->slotItem)->win_amount;
                if ((int)$booking->title_id === 3 && $booking->slotItem) {
                    $bookingDigitStr = str_pad((string)$booking->digits, 3, '0', STR_PAD_LEFT);
                    $winningDigitStr = str_pad((string)$booking->slotItem->digit, 3, '0', STR_PAD_LEFT);

                    if ($bookingDigitStr === $winningDigitStr) {
                        $singleWinAmount = (float)$booking->slotItem->first_price;
                        $booking->update(['first_price_flag' => 'true']);
                    } elseif (substr($bookingDigitStr, 1, 2) === substr($winningDigitStr, 1, 2)) {
                        $singleWinAmount = (float)$booking->slotItem->second_price;
                        $booking->update(['second_price_flag' => 'true']);
                    } elseif (substr($bookingDigitStr, 2, 1) === substr($winningDigitStr, 2, 1)) {
                        $singleWinAmount = (float)$booking->slotItem->third_price;
                        $booking->update(['third_price_flag' => 'true']);
                    }
                }

                $winnerData = [
                    'booking_id'        => $booking->booking_id,
                    'slot_id'           => $booking->slot_id,
                    'slot_items_id'     => $booking->slot_items_id,
                    'title'             => $booking->title_id,
                    'digits'            => $booking->digits,
                    'qty'               => $booking->qty,
                    'single_win_amount' => $singleWinAmount,
                    'win_amount'        => $booking->win_amount
                ];

                if ((int)$booking->title_id === 3) {
                    $threeDigitCount++;
                    if ($threeDigitCount === 1) {
                        $winnerData['first_price_flag'] = true;
                    } elseif ($threeDigitCount === 2) {
                        $winnerData['second_price_flag'] = true;
                    } elseif ($threeDigitCount === 3) {
                        $winnerData['third_price_flag'] = true;
                    }
                }

                $winningBookings[] = $winnerData;

                $totalWinAmount += (float) ($booking->win_amount ?? 0);
            }

            /*
            |--------------------------------------------------------------------------
            | Expired Slot List
            |--------------------------------------------------------------------------
            */

            if (
                $slot->draw_date < $currentDate &&
                !in_array($slot->slot_id, $expiredSlotIds)
            ) {

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

        if ((int)$booking->title_id === 3) {
            $winnerSlotItem = SlotItem::find($booking->slot_items_id);

            $isWinner = false;
            $winAmount = 0;
            $singleWinAmount = 0;
            $firstPriceFlag = false;
            $secondPriceFlag = false;
            $thirdPriceFlag = false;

            if (
                $winnerSlotItem &&
                $winnerSlotItem->slot_id == $booking->slot_id &&
                $winnerSlotItem->title == $booking->title_id
            ) {
                $bookingDigitStr = str_pad((string)$booking->digits, 3, '0', STR_PAD_LEFT);
                $winningDigitStr = str_pad((string)$winnerSlotItem->digit, 3, '0', STR_PAD_LEFT);

                if ($bookingDigitStr === $winningDigitStr) {
                    $isWinner = true;
                    $singleWinAmount = (float)$winnerSlotItem->first_price;
                    $winAmount = $singleWinAmount * $booking->qty;
                    $firstPriceFlag = true;
                } elseif (substr($bookingDigitStr, 1, 2) === substr($winningDigitStr, 1, 2)) {
                    $isWinner = true;
                    $singleWinAmount = (float)$winnerSlotItem->second_price;
                    $winAmount = $singleWinAmount * $booking->qty;
                    $secondPriceFlag = true;
                } elseif (substr($bookingDigitStr, 2, 1) === substr($winningDigitStr, 2, 1)) {
                    $isWinner = true;
                    $singleWinAmount = (float)$winnerSlotItem->third_price;
                    $winAmount = $singleWinAmount * $booking->qty;
                    $thirdPriceFlag = true;
                }
            }

            if ($isWinner) {
                $updateData = [
                    'is_winner' => "true",
                    'win_amount' => $winAmount
                ];
                if ($firstPriceFlag) {
                    $updateData['first_price_flag'] = 'true';
                }
                if ($secondPriceFlag) {
                    $updateData['second_price_flag'] = 'true';
                }
                if ($thirdPriceFlag) {
                    $updateData['third_price_flag'] = 'true';
                }
                $booking->update($updateData);

                $commissionPercentage = (float) ($slot->commission ?? 0);
                $commissionAmount = ($winAmount * $commissionPercentage) / 100;
                $creditAmount = $winAmount - $commissionAmount;

                $wallet = WalletRecharge::firstOrCreate(
                    ['customer_id' => $customer->customer_id],
                    ['balance' => 0]
                );

                $wallet->increment('balance', $creditAmount);
                WalletTransactions::create([
                    'customer_id'     => $customer->customer_id,
                    'type'            => 'credit',
                    'amount'          => $creditAmount,
                    'payment_method'  => 'slot win',
                    'reference_no'    => 'WIN-' . $booking->booking_id,
                    'remarks'         => 'Slot winning amount credited',
                ]);

                $totalWinAmount += $winAmount;

                $winnerData = [
                    'booking_id'        => $booking->booking_id,
                    'slot_id'           => $booking->slot_id,
                    'slot_items_id'     => $booking->slot_items_id,
                    'title'             => $booking->title_id,
                    'digits'            => $booking->digits,
                    'qty'               => $booking->qty,
                    'single_win_amount' => $singleWinAmount,
                    'win_amount'        => $winAmount
                ];

                if ((int)$booking->title_id === 3) {
                    $threeDigitCount++;
                    if ($threeDigitCount === 1) {
                        $winnerData['first_price_flag'] = true;
                    } elseif ($threeDigitCount === 2) {
                        $winnerData['second_price_flag'] = true;
                    } elseif ($threeDigitCount === 3) {
                        $winnerData['third_price_flag'] = true;
                    }
                }

                $winningBookings[] = $winnerData;
            } else {
                $booking->update([
                    'is_winner' => "false",
                    'win_amount' => 0
                ]);

                WalletTransactions::create([
                    'customer_id'     => $customer->customer_id,
                    'type'            => 'debit',
                    'amount'          => 0,
                    'payment_method'  => 'slot_loss',
                    'reference_no'    => 'LOSE-' . $booking->booking_id,
                    'remarks'         => 'No win for this booking',
                ]);
            }
        } else {
            // Existing logic remains unchanged for all other title_id values
            $winnerSlotItem = SlotItem::find($booking->slot_items_id);

            if (
                $winnerSlotItem &&
                $winnerSlotItem->slot_id == $booking->slot_id &&
                $winnerSlotItem->title == $booking->title_id &&
                $winnerSlotItem->digit == $booking->digits
            ) {

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
                | Wallet Update
                |--------------------------------------------------------------------------
                */

                $commissionPercentage = (float) ($slot->commission ?? 0);
                $commissionAmount = ($winAmount * $commissionPercentage) / 100;
                $creditAmount = $winAmount - $commissionAmount;

                $wallet = WalletRecharge::firstOrCreate(
                    ['customer_id' => $customer->customer_id],
                    ['balance' => 0]
                );

                $wallet->increment('balance', $creditAmount);
                WalletTransactions::create([
                    'customer_id'     => $customer->customer_id,
                    'type'            => 'credit', // win money so credit
                    'amount'          => $creditAmount,
                    'payment_method'  => 'slot win',
                    'reference_no'    => 'WIN-' . $booking->booking_id,
                    'remarks'         => 'Slot winning amount credited',
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
                    'win_amount'        => $winAmount
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

                WalletTransactions::create([
                    'customer_id'     => $customer->customer_id,
                    'type'            => 'debit',
                    'amount'          => 0,
                    'payment_method'  => 'slot_loss',
                    'reference_no'    => 'LOSE-' . $booking->booking_id,
                    'remarks'         => 'No win for this booking',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Expired Slot Add
        |--------------------------------------------------------------------------
        */

        if (
            $slot->draw_date < $currentDate &&
            !in_array($slot->slot_id, $expiredSlotIds)
        ) {

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
}
