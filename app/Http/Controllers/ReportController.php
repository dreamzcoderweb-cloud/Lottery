<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Slot;
use App\Models\SlotItem;
use Carbon\Carbon;

use Illuminate\Http\Request;

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

            // Only slots whose result process completed
            ->whereHas('bookings', function ($q) {
                $q->whereNotNull('is_winner');
            })

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
                $today = now($tz)->toDateString();
                $currentTime = now($tz)->format('H:i:s');

                $query->where(function ($q) use ($today, $currentTime) {
                    $q->whereDate('draw_date', '<', $today)
                        ->orWhere(function ($sub) use ($today, $currentTime) {
                            $sub->whereDate('draw_date', $today)
                                ->whereTime('booking_close_time', '<=', $currentTime);
                        });
                });
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
                    'digit'      => (int) $item->digit,
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

        // Fetch all bookings for this slot with customer details
        $bookings = Booking::where('slot_id', $slot->slot_id)
            ->whereNotNull('is_winner')
            ->with(['customer', 'slotItem'])
            ->get();

        foreach ($bookings as $booking) {
            $bookingTime = null;
            if (!empty($booking->booking_time)) {
                $bookingTime = Carbon::parse($booking->booking_time, $tz)->format('d-m-Y h:i A');
            }

            $bookingData = [
                'booking_id' => $booking->booking_id,
                'customer_name' => $booking->customer->name ?? 'N/A',
                'customer_mobile' => $booking->customer->mobile ?? 'N/A',
                'customer_id' => $booking->customer_id,
                'ticket_number' => $booking->booking_id, // Using booking_id as ticket number
                'slot_digit' => $booking->slotItem->digit ?? '-',
                'group_name' => strtoupper($booking->slotItem->group_name ?? 'N/A'),
                'ticket_amount' => (float) ($booking->amount ?? 0),
                'ticket_amt' => (float) ($booking->slotItem->ticket_amt ?? 0),
                'booking_time' => $bookingTime,
                'quantity' => $booking->qty,
                'win_amount' => $booking->is_winner ? ((float) ($booking->win_amount ?? 0)) : 0,
            ];

            if ($booking->is_winner) {
                $details['winners'][] = $bookingData;
            } else {
                $details['losers'][] = $bookingData;
            }
        }

        return $details;
    }

    /**
     * Display customer details for a specific slot
     */
    public function slotCustomerDetails($slot_id)
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
                    'digit' => (int) $item->digit,
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

        // Prepare data
        $data = [
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
            ]
        ];

        return view('Report.slot-details', compact('data'));
    }
}
