<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slot;
use App\Models\SlotItem;

class SlotController extends Controller
{
    public function index()
    {
        $slots = Slot::with([
                'items:slot_id,win_amount,ticket_amt,digit'
            ])
            ->where('status', 'Active')
            ->whereDate('draw_date', today())
            ->get()
            ->reject(function ($slot) {
                // Check 1: Exclude if slot items have updated/non-empty winning digits
                $hasWinningDigits = $slot->items->contains(function ($item) {
                    return !is_null($item->digit) && trim((string) $item->digit) !== '';
                });

                if ($hasWinningDigits) {
                    return true;
                }

                // Check 2: Exclude if slot bookings have already been resulted (is_winner is not null)
                $hasResultedBookings = \App\Models\Booking::where('slot_id', $slot->slot_id)
                    ->whereNotNull('is_winner')
                    ->exists();

                if ($hasResultedBookings) {
                    return true;
                }

                return false;
            })
            ->map(function ($slot) {
                $slot->items->makeHidden(['digit']);
                return $slot;
            })
            ->values();

        return response()->json([
            'message' => 'Available slots retrieved successfully',
            'slots' => $slots,
        ]);
    }

    public function show($id)
    {
        $slot = Slot::find($id);
        $slotitems = SlotItem::where('slot_id', $id)->get();

        if(!$slotitems) {
            return response()->json([
                'message' => 'No sub-slots found for the given slot ID',
                'sub_slots' => [],
            ], 404);

        }
        return response()->json([
            'message' => 'Sub-slots retrieved successfully',
            'sub_slots' => $slotitems,
        ]);
    }
}
