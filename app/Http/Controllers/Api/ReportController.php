<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Slot;
use App\Models\SlotItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
class ReportController extends Controller
{

    public function slotWinningReport(): \Illuminate\Http\JsonResponse
    {
        $tz = 'Asia/Kolkata';

        $limit = (int) request()->query('limit', 30);
        $limit = max(1, min(100, $limit));

        $title = request()->query('title');

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
                    ]);

                    if (!empty($title)) {
                        $q->where('title', $title);
                    }
                }
            ])

            ->where('status', 'Active')

            ->where(function ($q) use ($tz) {

                $today = now($tz)->toDateString();
                $currentTime = now($tz)->format('H:i:s');

                $q->whereDate('draw_date', '<', $today)

                    ->orWhere(function ($sub) use ($today, $currentTime) {

                        $sub->whereDate('draw_date', $today)
                            ->whereTime('booking_close_time', '<=', $currentTime);
                    });
            })

            ->orderByDesc('draw_date')
            ->orderByDesc('draw_time')
            ->limit($limit)
            ->get();

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

            $groups = $slot->items->map(function ($item) {

                return [
                    'group_name' => strtoupper($item->group_name),
                    'digit'      => (int) $item->digit,
                    'color'      => $item->color,
                    'win_amount' => $item->win_amount,
                    'ticket_amt' => $item->ticket_amt,
                ];
            })->values();

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
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Last winning slot records',
            'data' => $data,
        ]);
    }
    /**
     * Customer report:
     * customer_id -> slots -> booked items -> draw_date -> winner info.
     */
    public function customerTickets(Request $request): \Illuminate\Http\JsonResponse
    {
        $customer = auth()->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $customerId = (int) ($request->query('customer_id', $customer->customer_id));

        // Security: only allow self (until roles/permissions are defined).
        if ($customerId !== (int) $customer->customer_id) {
            return response()->json([
                'status' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $bookings = Booking::query()
            ->with([
                'slot:slot_id,main_title,short_title,draw_date,booking_close_time,draw_time,status',
                'slotItem:slot_items_id,slot_id,group_name,digit,color,win_amount,ticket_amt',
            ])
            ->where('customer_id', $customerId)
            ->orderByDesc('booking_id')
            ->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No bookings found',
                'data' => [
                    'customer_id' => $customerId,
                    'slots' => [],
                ],
            ]);
        }

        $now = Carbon::now('Asia/Kolkata');

        $pending = $bookings->filter(fn($b) =>
            !$b->is_winner &&
            !is_null($b->digits) &&
            !is_null($b->title_id)
        );

        $winnerMap = [];
        if ($pending->isNotEmpty()) {
            $slotIds = $pending->pluck('slot_id')->unique()->values();
            $titles = $pending->pluck('title_id')->unique()->values();
            $digits = $pending->pluck('digits')->unique()->values();

            $winnerItems = SlotItem::query()
                ->select(['slot_id', 'title', 'digit', 'win_amount'])
                ->whereIn('slot_id', $slotIds)
                ->whereIn('title', $titles)
                ->whereIn('digit', $digits)
                ->get();

            foreach ($winnerItems as $wi) {
                $winnerMap[$wi->slot_id . '|' . $wi->title . '|' . $wi->digit] = $wi->win_amount;
            }
        }

        $slots = $bookings
            ->groupBy('slot_id')
            ->map(function ($slotBookings) use ($now, $winnerMap) {
                /** @var \App\Models\Booking $first */
                $first = $slotBookings->first();
                $slot = $first->slot;

                $drawDate = $slot?->draw_date;
                $closeTime = $slot?->booking_close_time;

                $closeAt = null;
                if ($drawDate && $closeTime) {
                    $closeAt = Carbon::parse($drawDate . ' ' . $closeTime, 'Asia/Kolkata');
                }

                $isClosed = $closeAt ? $now->gte($closeAt) : null;

                $items = $slotBookings->map(function ($b) use ($isClosed, $winnerMap) {
                    $key = $b->slot_id . '|' . ($b->title_id ?? '') . '|' . ($b->digits ?? '');
                    $computedWinAmount = $winnerMap[$key] ?? null;
                    $computedIsWinner = $isClosed === true && !is_null($computedWinAmount);

                    return [
                        'booking_id' => $b->booking_id,
                        'slot_items_id' => $b->slot_items_id,
                        'title_id' => $b->title_id,
                        'digits' => $b->digits ?? null,
                        'qty' => $b->qty,
                        'amount' => $b->amount,
                        'status' => $b->status,
                        'payment_status' => $b->payment_status,
                        'booking_time' => $b->booking_time ?? null,
                        'close_time' => $b->close_time ?? null,
                        'is_winner' => $b->is_winner ?? false,
                        'win_amount' => $b->win_amount ?? 0,
                        'computed_is_winner' => $computedIsWinner,
                        'computed_win_amount' => $computedWinAmount,
                        'slot_item' => $b->slotItem ? [
                            'slot_items_id' => $b->slotItem->slot_items_id,
                            'group_name' => $b->slotItem->group_name,
                            'digit' => $b->slotItem->digit,
                            'color' => $b->slotItem->color,
                            'win_amount' => $b->slotItem->win_amount,
                            'ticket_amt' => $b->slotItem->ticket_amt,
                        ] : null,
                    ];
                })->values();

                $totalAmount = $slotBookings->sum('amount');
                $totalQty = $slotBookings->sum('qty');
                $totalWinAmount = $slotBookings->sum(function ($b) {
                    return (float) ($b->win_amount ?? 0);
                });
                $winnersCount = $slotBookings->filter(function ($b) {
                    return (int) ($b->is_winner ?? 0) === 1;
                })->count();

                return [
                    'slot' => $slot ? [
                        'slot_id' => $slot->slot_id,
                        'main_title' => $slot->main_title,
                        'short_title' => $slot->short_title,
                        'draw_date' => $slot->draw_date,
                        'booking_close_time' => $slot->booking_close_time,
                        'draw_time' => $slot->draw_time,
                        'status' => $slot->status,
                    ] : null,
                    'draw' => [
                        'close_at' => $closeAt?->toDateTimeString(),
                        'is_closed' => $isClosed,
                        'server_time' => $now->toDateTimeString(),
                    ],
                    'summary' => [
                        'total_amount' => $totalAmount,
                        'total_qty' => $totalQty,
                        'winners_count' => $winnersCount,
                        'total_win_amount' => $totalWinAmount,
                    ],
                    'items' => $items,
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'Customer report',
            'data' => [
                'customer_id' => $customerId,
                'slots' => $slots,
            ],
        ]);
    }
}
