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
        'items:slot_id,win_amount,ticket_amt'])->where('status', 'Active')->get();

        return response()->json([
            'message' => 'Available slots retrieved successfully',
            'slots' => $slots,
        ]);
    }

    public function show($id)
    {
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
