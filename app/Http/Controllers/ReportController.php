<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Slot;
use App\Models\SlotItem;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function winningsSlotsReport(Request $request)
    {

        $tz = 'Asia/Kolkata';

        $limit = (int) request()->query('limit', 30);
        $limit = max(1, min(100, $limit));

        $title = request()->query('title');

        $date = request()->query('date');

        $slots = Slot::query()
            ->select([
                'slot_id',
                'main_title',
                'draw_date',
                'booking_close_time',
                'draw_time',
                'short_title',
                'title',
                'slug',
                'status',
            ])

            ->whereHas('bookings')

            ->with([
                'items' => function ($q) use ($title) {
                    $q->select([
                        'slot_items_id',
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
                    ]);

                    if (!empty($title)) {
                        $q->where('title', $title);
                    }
                }
            ])

            ->where('status', 'Active')

            ->when($date, function ($query, $date) {
                $query->whereDate('draw_date', $date);
            })

            ->when(!$date, function ($query) use ($tz) {
                // Default: include slots scheduled for today or earlier.
                // Previously the logic restricted today's slots by booking_close_time,
                // causing today's records to be hidden on initial load. We want
                // today's slots to appear by default (matching the filter behavior
                // when the user explicitly selects today's date).
                $today = now($tz)->toDateString();

                $query->whereDate('draw_date', '<=', $today);
            })

            ->orderByDesc('draw_date')
            ->orderByDesc('draw_time')
            ->limit($limit)
            ->get();

        $bookingSummary = Booking::query()
            ->selectRaw('slot_id, slot_items_id, SUM(qty) as total_qty, SUM(amount) as total_amount')
            ->whereIn('slot_id', $slots->pluck('slot_id'))
            ->groupBy('slot_id', 'slot_items_id')
            ->get()
            ->keyBy(fn ($booking) => $booking->slot_id . '|' . $booking->slot_items_id);

        $data = [];

        foreach ($slots as $slot) {

            if ($slot->items->isEmpty()) {
                continue;
            }

            $resultTime = null;

            if (!empty($slot->draw_time)) {
                $resultTime = Carbon::parse(
                    $slot->draw_time,
                    $tz
                )->format('h:i A');
            }

            $groups = $slot->items->map(function ($item) use ($bookingSummary) {
                $summaryKey = $item->slot_id . '|' . $item->slot_items_id;
                $summary = $bookingSummary->get($summaryKey);

                return [
                    'slot_items_id' => $item->slot_items_id,
                    'title'       => $item->title,
                    'group_name' => strtoupper($item->group_name),
                    'digit'      => $item->digit,
                    'color'      => $item->color,
                    'win_amount' => $item->win_amount,
                    'ticket_amt' => $item->ticket_amt,
                    'first_price' => $item->first_price,
                    'second_price' => $item->second_price,
                    'third_price' => $item->third_price,
                    'booking_qty' => (int) ($summary->total_qty ?? 0),
                    'booking_amount' => (float) ($summary->total_amount ?? 0),
                ];
            })->values();

            // Fetch customer booking details for all slots
            $customerDetails = $this->getCustomerBookingDetails($slot, $tz);

            $slotWinnerBookings = Booking::where('slot_id', $slot->slot_id)
                ->where(function ($q) {
                    $q->where('is_winner', 'true')
                      ->orWhere('is_winner', true)
                      ->orWhere('is_winner', 1)
                      ->orWhere('is_winner', '1');
                })
                ->where('win_amount', '>', 0)
                ->get();

            $pendingCount = $slotWinnerBookings->where('winning_approved', false)->count();
            $approvedCount = $slotWinnerBookings->where('winning_approved', true)->count();
            $slotTotalWinAmount = $slotWinnerBookings->sum('win_amount');

            $data[] = [
                'slot_id'            => $slot->slot_id,
                'main_title'         => $slot->main_title,
                'short_title'        => $slot->short_title,
                'title'              => $slot->title,
                'slug'               => $slot->slug,
                'draw_date'          => $slot->draw_date,
                'draw_time'          => $resultTime,
                'booking_close_time' => $slot->booking_close_time,
                'status'             => $slot->status,
                'winning_groups'     => $groups,
                'customer_details'   => $customerDetails,
                'winning_summary'    => [
                    'has_winners'     => $slotWinnerBookings->count() > 0,
                    'pending_count'   => $pendingCount,
                    'approved_count'  => $approvedCount,
                    'total_win_amount'=> $slotTotalWinAmount,
                ],
            ];
        }

        return view('Report.winnings_slots', compact('data'));
    }

    /**
     * Fetch customer booking details (winners and losers) for a single-digit slot
     */
    private function getCustomerBookingDetails($slot, $tz)
    {
        $details = [
            'winners' => [],
            'losers' => [],
        ];

        // Preload all slot items for this slot to allow dynamic matching
        $slotItems = SlotItem::where('slot_id', $slot->slot_id)->get();

        // Fetch all bookings for this slot with customer details
        $bookings = Booking::where('slot_id', $slot->slot_id)
            ->with(['customer', 'slotItem'])
            ->get();
        foreach ($bookings as $booking) {
            $bookingTime = null;
            if (!empty($booking->booking_time)) {
                $bookingTime = Carbon::parse($booking->booking_time, $tz)->format('d-m-Y h:i A');
            }

            $isWinner = $booking->is_winner === true || $booking->is_winner === 'true' || $booking->is_winner === 1 || $booking->is_winner === '1';

            $qty = max(1, (int)$booking->qty);

            $currentSlotItem = $booking->slotItem;
            $bookingAmt = (float)($booking->amount ?? 0);
            $unitAmt = $bookingAmt / $qty;

            if (!$currentSlotItem || ((float)$currentSlotItem->ticket_amt !== $bookingAmt && (float)$currentSlotItem->ticket_amt !== $unitAmt)) {
                $correctSlotItem = $slotItems->filter(function ($item) use ($booking, $currentSlotItem, $bookingAmt, $unitAmt) {
                    if ((int)$item->title !== (int)$booking->title_id) {
                        return false;
                    }
                    if ($currentSlotItem && $item->group_name !== $currentSlotItem->group_name) {
                        return false;
                    }
                    return (float)$item->ticket_amt === $bookingAmt || (float)$item->ticket_amt === $unitAmt;
                })->first();

                if ($correctSlotItem) {
                    $currentSlotItem = $correctSlotItem;
                }
            }

            // Create one entry for each quantity
            for ($i = 1; $i <= $qty; $i++) {

                $bookingData = [
                    'booking_id'        => $booking->booking_id,
                    'customer_name'     => $booking->customer->name ?? 'N/A',
                    'customer_mobile'   => $booking->customer->mobile ?? 'N/A',
                    'customer_id'       => $booking->customer_id,

                    // Ticket Number
                    'ticket_number'     => $booking->booking_id . '-' . $i,

                    'slot_items_id'     => $currentSlotItem ? $currentSlotItem->slot_items_id : $booking->slot_items_id,
                    'slot_digit'        => $currentSlotItem->digit ?? '-',
                    'booked_digits'     => $booking->digits ?? '-',
                    'group_name'        => strtoupper($currentSlotItem->group_name ?? 'N/A'),
                    'ticket_amount'     => (float)($booking->amount ?? 0),
                    'ticket_amt'        => (float)($currentSlotItem->ticket_amt ?? 0),
                    'booking_time'      => $bookingTime,
                    'quantity'          => 1,
                    'win_amount'        => $isWinner ? ((float)($booking->win_amount ?? 0) / $qty) : 0,
                    'winning_approved'  => (bool)$booking->winning_approved,
                ];

                if ($isWinner) {
                    $details['winners'][] = $bookingData;
                } else {
                    $details['losers'][] = $bookingData;
                }
            }
        }
        return $details;
    }

    /**
     * Fetch formatted slot report data
     */
    private function getSlotReportData($slot_id)
    {
        $tz = 'Asia/Kolkata';

        // Fetch the slot with its items and bookings
        $slot = Slot::with(['items', 'bookings'])
            ->findOrFail($slot_id);

        // Format slot data
        $titleLabels = [
            '1' => 'Single Digit',
            '2' => 'Double Digit',
            '3' => 'Three Digit',
            '4' => 'Four Digit',
            '5' => 'Five Digit',
        ];

        $titleText = collect(explode(',', (string) ($slot->title ?? '')))
            ->map(fn ($title) => trim($title))
            ->filter()
            ->map(fn ($title) => $titleLabels[$title] ?? $title)
            ->implode(', ');

        $resultTime = null;
        if (!empty($slot->draw_time)) {
            $resultTime = Carbon::parse($slot->draw_time, $tz)->format('h:i A');
        }

        // Get winning groups
        $winningGroups = SlotItem::where('slot_id', $slot_id)
            ->select(['slot_items_id', 'title', 'group_name', 'digit', 'color', 'win_amount', 'ticket_amt', 'first_price', 'second_price', 'third_price'])
            ->get()
            ->map(function ($item) {
                return [
                    'slot_items_id' => $item->slot_items_id,
                    'title' => $item->title,
                    'group_name' => strtoupper($item->group_name),
                    'digit' => $item->digit,
                    'color' => $item->color,
                    'win_amount' => $item->win_amount,
                    'ticket_amt' => $item->ticket_amt,
                    'first_price' => $item->first_price,
                    'second_price' => $item->second_price,
                    'third_price' => $item->third_price,
                ];
            });

        // Get customer details
        $customerDetails = $this->getCustomerBookingDetails($slot, $tz);
        // Calculate summary stats
        $winners = $customerDetails['winners'] ?? [];
        $losers = $customerDetails['losers'] ?? [];
        $totalTickets = count($winners) + count($losers);
        $winPercentage = $totalTickets > 0 ? round((count($winners) / $totalTickets) * 100) : 0;
        $totalWinAmount = array_sum(array_column($winners, 'win_amount'));
        $totalInvested = array_sum(array_column($losers, 'ticket_amount'));

        $winnerBookingIds = [];
        $pendingWinnersCount = 0;
        $approvedWinnersCount = 0;
        foreach ($winners as $w) {
            $bId = $w['booking_id'];
            if (!isset($winnerBookingIds[$bId])) {
                $winnerBookingIds[$bId] = true;
                if (!empty($w['winning_approved'])) {
                    $approvedWinnersCount++;
                } else {
                    $pendingWinnersCount++;
                }
            }
        }

        return [
            'slot_id' => $slot->slot_id,
            'main_title' => $slot->main_title,
            'short_title' => $slot->short_title,
            'title' => $titleText,
            'draw_date' => $slot->draw_date,
            'draw_time' => $resultTime,
            'booking_close_time' => $slot->booking_close_time,
            'status' => $slot->status,
            'winning_groups' => $winningGroups,
            'customer_details' => $customerDetails,
            'summary' => [
                'total_tickets' => $totalTickets,
                'total_winners' => count($winners),
                'total_losers' => count($losers),
                'win_percentage' => $winPercentage,
                'total_win_amount' => $totalWinAmount,
                'total_invested' => $totalInvested,
                'pending_winners_count' => $pendingWinnersCount,
                'approved_winners_count' => $approvedWinnersCount,
            ]
        ];
    }

    /**
     * Display customer details for a specific slot
     */
    public function slotCustomerDetails($slot_id)
    {
        $data = $this->getSlotReportData($slot_id);
        return view('Report.slot-details', compact('data'));
    }

    /**
     * Display winning and losing ticket details for a specific slot
     */
    public function slotTickets($slot_id)
    {
        $data = $this->getSlotReportData($slot_id);
        return view('Report.slot-tickets', compact('data'));
    }

    public function approveBookingWinning(Request $request, $booking_id)
    {
        try {
            $approvalDetail = null;
            DB::transaction(function () use ($booking_id, &$approvalDetail) {
                $booking = Booking::with('customer')->lockForUpdate()->findOrFail($booking_id);

                $isWinner = $booking->is_winner === true || $booking->is_winner === 'true' || $booking->is_winner === 1 || $booking->is_winner === '1';

                if (!$isWinner || (float)$booking->win_amount <= 0) {
                    throw new \Exception('This booking is not a winning ticket or has zero winning amount.');
                }

                $existingTx = WalletTransactions::where('customer_id', $booking->customer_id)
                    ->where('reference_no', 'WIN-' . $booking->booking_id)
                    ->first();

                if ($booking->winning_approved && $existingTx) {
                    throw new \Exception('This winning amount has already been approved and credited.');
                }

                if (!$existingTx) {
                    $wallet = WalletRecharge::firstOrCreate(
                        ['customer_id' => $booking->customer_id],
                        ['balance' => 0]
                    );
                    $wallet->increment('balance', (float)$booking->win_amount);

                    WalletTransactions::create([
                        'customer_id'     => $booking->customer_id,
                        'type'            => 'credit',
                        'amount'          => (float)$booking->win_amount,
                        'payment_method'  => 'slot win',
                        'reference_no'    => 'WIN-' . $booking->booking_id,
                        'remarks'         => 'Winning Amount',
                    ]);
                }

                $booking->winning_approved = true;
                $booking->save();

                $approvalDetail = [
                    'slot_id'         => (int) $booking->slot_id,
                    'title_id'        => (int) $booking->title_id,
                    'digits'          => (string) ($booking->digits ?? ''),
                    'winning_amount'  => (float) $booking->win_amount,
                    'approval_status' => 'approved',
                    'booking_id'      => (int) $booking->booking_id,
                    'customer_id'     => (int) $booking->customer_id,
                    'customer_name'   => $booking->customer ? $booking->customer->name : null,
                    'customer_mobile' => $booking->customer ? $booking->customer->mobile : null,
                    'slot_items_id'   => $booking->slot_items_id ? (int) $booking->slot_items_id : null,
                    'qty'             => (int) $booking->qty,
                    'amount'          => (float) $booking->amount,
                    'winning_approved'=> 1,
                ];
            });

            $isJson = $request->expectsJson()
                || $request->ajax()
                || $request->wantsJson()
                || $request->isJson()
                || $request->is('api/*')
                || !$request->accepts('text/html');

            if ($isJson) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Winning amount approved successfully',
                    'data'    => $approvalDetail,
                ]);
            }

            return redirect()->back()->with('success', 'Winning amount approved and credited to user wallet successfully.');
        } catch (\Exception $e) {
            $isJson = $request->expectsJson()
                || $request->ajax()
                || $request->wantsJson()
                || $request->isJson()
                || $request->is('api/*')
                || !$request->accepts('text/html');

            if ($isJson) {
                return response()->json([
                    'status'  => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return redirect()->back()->with('danger', 'Error: ' . $e->getMessage());
        }
    }

    public function approveSlotWinnings(Request $request, $slot_id)
    {
        try {
            $approvedCount = 0;
            $approvalDetails = [];
            DB::transaction(function () use ($slot_id, &$approvedCount, &$approvalDetails) {
                $winningBookings = Booking::where('slot_id', $slot_id)
                    ->where(function ($q) {
                        $q->where('is_winner', 'true')
                          ->orWhere('is_winner', true)
                          ->orWhere('is_winner', 1)
                          ->orWhere('is_winner', '1');
                    })
                    ->where('win_amount', '>', 0)
                    ->with('customer')
                    ->lockForUpdate()
                    ->get();

                foreach ($winningBookings as $booking) {
                    $existingTx = WalletTransactions::where('customer_id', $booking->customer_id)
                        ->where('reference_no', 'WIN-' . $booking->booking_id)
                        ->first();

                    if (!$existingTx) {
                        $wallet = WalletRecharge::firstOrCreate(
                            ['customer_id' => $booking->customer_id],
                            ['balance' => 0]
                        );
                        $wallet->increment('balance', (float)$booking->win_amount);

                        WalletTransactions::create([
                            'customer_id'     => $booking->customer_id,
                            'type'            => 'credit',
                            'amount'          => (float)$booking->win_amount,
                            'payment_method'  => 'slot win',
                            'reference_no'    => 'WIN-' . $booking->booking_id,
                            'remarks'         => 'Winning Amount',
                        ]);
                    }

                    if (!$booking->winning_approved) {
                        $booking->winning_approved = true;
                        $booking->save();
                        $approvedCount++;
                    }

                    $approvalDetails[] = [
                        'slot_id'         => (int) $booking->slot_id,
                        'title_id'        => (int) $booking->title_id,
                        'digits'          => (string) ($booking->digits ?? ''),
                        'winning_amount'  => (float) $booking->win_amount,
                        'approval_status' => 'approved',
                        'booking_id'      => (int) $booking->booking_id,
                        'customer_id'     => (int) $booking->customer_id,
                        'customer_name'   => $booking->customer ? $booking->customer->name : null,
                        'customer_mobile' => $booking->customer ? $booking->customer->mobile : null,
                        'slot_items_id'   => $booking->slot_items_id ? (int) $booking->slot_items_id : null,
                        'qty'             => (int) $booking->qty,
                        'amount'          => (float) $booking->amount,
                        'winning_approved'=> 1,
                    ];
                }
            });

            $isJson = $request->expectsJson()
                || $request->ajax()
                || $request->wantsJson()
                || $request->isJson()
                || $request->is('api/*')
                || !$request->accepts('text/html');

            if ($isJson) {
                $message = $approvedCount > 0
                    ? "{$approvedCount} winning amount(s) approved successfully"
                    : "All winning amounts for this slot were already approved";

                return response()->json([
                    'status'  => true,
                    'message' => $message,
                    'data'    => $approvalDetails,
                ]);
            }

            if ($approvedCount > 0) {
                return redirect()->back()->with('success', "{$approvedCount} winning amount(s) approved and credited successfully.");
            }

            return redirect()->back()->with('success', 'All winning amounts for this slot were already approved.');
        } catch (\Exception $e) {
            $isJson = $request->expectsJson()
                || $request->ajax()
                || $request->wantsJson()
                || $request->isJson()
                || $request->is('api/*')
                || !$request->accepts('text/html');

            if ($isJson) {
                return response()->json([
                    'status'  => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return redirect()->back()->with('danger', 'Error: ' . $e->getMessage());
        }
    }
}
