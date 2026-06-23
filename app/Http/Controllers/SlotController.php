<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slot;
use App\Models\SlotItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SlotController extends Controller
{
    private function normalizeMoneyInputs(Request $request): void
    {
        foreach (['win_amount', 'ticket_amt', 'first_price', 'second_price', 'third_price', 'win_amount_by_title', 'ticket_amt_by_title'] as $key) {
            if (!$request->has($key)) continue;
            $arr = $request->input($key);
            if (!is_array($arr)) continue;

            $normalized = [];
            foreach ($arr as $k => $v) {
                if ($v === null) {
                    $normalized[$k] = null;
                    continue;
                }
                $s = trim((string) $v);
                if ($s === '') {
                    $normalized[$k] = null;
                    continue;
                }
                // allow "1,000.50" style input
                $s = str_replace(',', '', $s);
                $normalized[$k] = $s;
            }
            $request->merge([$key => $normalized]);
        }
    }

    private function normalizeNumericCommaTitle(Request $request): void
    {
        if (!$request->has('title')) {
            return;
        }

        $title = trim((string) $request->input('title', ''));
        $title = preg_replace('/\s+/', '', $title);
        $title = preg_replace('/,+/', ',', $title);
        $title = trim((string) $title, ',');

        $request->merge(['title' => $title]);
    }

    private function normalizeSlotItems(Request $request): void
    {
        $groupNames = $request->input('group_name', []);
        $digits = $request->input('digit', []);
        $colors = $request->input('color', []);
        $winAmounts = $request->input('win_amount', []);
        $ticketAmounts = $request->input('ticket_amt', []);
        $firstPrices = $request->input('first_price', []);
        $secondPrices = $request->input('second_price', []);
        $thirdPrices = $request->input('third_price', []);
        $slotItemIds = $request->input('slot_item_id', []);
        $itemTitleCounts = $request->input('item_title_count', []);

        if (!is_array($groupNames)) $groupNames = [];
        if (!is_array($digits)) $digits = [];
        if (!is_array($colors)) $colors = [];
        if (!is_array($winAmounts)) $winAmounts = [];
        if (!is_array($ticketAmounts)) $ticketAmounts = [];
        if (!is_array($firstPrices)) $firstPrices = [];
        if (!is_array($secondPrices)) $secondPrices = [];
        if (!is_array($thirdPrices)) $thirdPrices = [];
        if (!is_array($slotItemIds)) $slotItemIds = [];
        if (!is_array($itemTitleCounts)) $itemTitleCounts = [];

        $normalizedGroupNames = [];
        $normalizedDigits = [];
        $normalizedColors = [];
        $normalizedWinAmounts = [];
        $normalizedTicketAmounts = [];
        $normalizedFirstPrices = [];
        $normalizedSecondPrices = [];
        $normalizedThirdPrices = [];
        $normalizedIds = [];
        $normalizedItemTitleCounts = [];

        $max = max(count($groupNames), count($digits), count($colors), count($winAmounts), count($ticketAmounts), count($firstPrices), count($secondPrices), count($thirdPrices), count($slotItemIds), count($itemTitleCounts));

        for ($i = 0; $i < $max; $i++) {
            $g = isset($groupNames[$i]) ? trim((string) $groupNames[$i]) : '';
            $d = $digits[$i] ?? null;
            $c = isset($colors[$i]) ? trim((string) $colors[$i]) : null;
            $w = $winAmounts[$i] ?? null;
            $t = $ticketAmounts[$i] ?? null;
            $fp = $firstPrices[$i] ?? null;
            $sp = $secondPrices[$i] ?? null;
            $tp = $thirdPrices[$i] ?? null;
            $id = $slotItemIds[$i] ?? null;
            $tc = $itemTitleCounts[$i] ?? null;

            // Keep only rows that actually have data. Require group_name + digit; color optional.
            if ($g === '' && ($d === null || $d === '')) {
                continue;
            }

            $w = ($w !== null && trim((string) $w) !== '') ? trim((string) $w) : null;
            $t = ($t !== null && trim((string) $t) !== '') ? trim((string) $t) : null;
            $fp = ($fp !== null && trim((string) $fp) !== '') ? trim((string) $fp) : null;
            $sp = ($sp !== null && trim((string) $sp) !== '') ? trim((string) $sp) : null;
            $tp = ($tp !== null && trim((string) $tp) !== '') ? trim((string) $tp) : null;

            $normalizedGroupNames[] = $g;
            $normalizedDigits[] = $d;
            $normalizedColors[] = $c;
            $normalizedWinAmounts[] = $w;
            $normalizedTicketAmounts[] = $t;
            $normalizedFirstPrices[] = $fp;
            $normalizedSecondPrices[] = $sp;
            $normalizedThirdPrices[] = $tp;
            $normalizedIds[] = $id;
            $normalizedItemTitleCounts[] = $tc;
        }

        $request->merge([
            'group_name' => $normalizedGroupNames,
            'digit' => $normalizedDigits,
            'color' => $normalizedColors,
            'win_amount' => $normalizedWinAmounts,
            'ticket_amt' => $normalizedTicketAmounts,
            'first_price' => $normalizedFirstPrices,
            'second_price' => $normalizedSecondPrices,
            'third_price' => $normalizedThirdPrices,
            'slot_item_id' => $normalizedIds,
            'item_title_count' => $normalizedItemTitleCounts,
        ]);
    }

    private function makeUniqueSlug(string $mainTitle, ?int $ignoreSlotId = null): string
    {
        $base = Str::slug($mainTitle);
        $base = $base !== '' ? $base : 'slot';

        $slug = $base;
        $suffix = 1;

        while (Slot::withTrashed()
            ->when($ignoreSlotId, fn ($q) => $q->where('slot_id', '!=', $ignoreSlotId))
            ->where('slug', $slug)
            ->exists()) {
            $suffix++;
            $slug = $base . '-' . $suffix;
        }

        return $slug;
    }

    public function index(){
   $slots = Slot::with('items')
    ->orderBy('slot_id', 'desc')
    ->get();

    return view('slots.view', compact('slots'));
    }
    public function add(Request $request){

        if ($request->isMethod('get')) {
            return view('slots.add');
        }

        $this->normalizeMoneyInputs($request);
        $this->normalizeNumericCommaTitle($request);
        $this->normalizeSlotItems($request);

        $validator = Validator::make($request->all(), [
            'main_title' => ['required', 'string', 'max:255'],
            'draw_time' => ['required'],
            'draw_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_close_time' => ['required'],
            'short_title' => ['required', 'string', 'max:255'],
            'commission' => ['required', 'numeric', 'min:0', 'max:100'],
            'title' => ['required', 'string', 'max:255', 'regex:/^\\d+(?:,\\d+)*$/'],
            'win_amount' => ['nullable', 'array'],
            'win_amount.*' => ['nullable', 'numeric', 'min:0'],
            'ticket_amt' => ['required', 'array', 'min:1'],
            'ticket_amt.*' => ['nullable', 'numeric', 'min:0'],
            'first_price' => ['nullable', 'array'],
            'first_price.*' => ['nullable', 'numeric', 'min:0'],
            'second_price' => ['nullable', 'array'],
            'second_price.*' => ['nullable', 'numeric', 'min:0'],
            'third_price' => ['nullable', 'array'],
            'third_price.*' => ['nullable', 'numeric', 'min:0'],
            'win_amount_by_title' => ['nullable', 'array'],
            'win_amount_by_title.*' => ['nullable', 'numeric', 'min:0'],
            'ticket_amt_by_title' => ['nullable', 'array'],
            'ticket_amt_by_title.*' => ['nullable', 'numeric', 'min:0'],
            'group_name' => ['required', 'array', 'min:1'],
            'group_name.*' => ['required', 'string', 'max:255'],
            'digit' => ['required', 'array', 'min:1'],
            'digit.*' => ['required'],
            'item_title_count' => ['required', 'array', 'min:1'],
            'item_title_count.*' => ['required', 'integer', 'in:1,2,3,4,5'],
            'color' => ['nullable', 'array'],
            'color.*' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $groups = $request->input('group_name', []);
            $digits = $request->input('digit', []);
            $tcs = $request->input('item_title_count', []);
            $tickets = $request->input('ticket_amt', []);
            $firstPrices = $request->input('first_price', []);
            $secondPrices = $request->input('second_price', []);
            $thirdPrices = $request->input('third_price', []);

            $count = max(count($groups), count($digits), count($tcs));
            $seenGroupNames = [];
            $duplicateGroupIndexes = [];
            for ($i = 0; $i < $count; $i++) {
                $tc = (int) ($tcs[$i] ?? 0);
                if (!in_array($tc, [1, 2, 3, 4, 5], true)) {
                    $validator->errors()->add("item_title_count.$i", 'Invalid title count.');
                    continue;
                }

                $g = isset($groups[$i]) ? (string) $groups[$i] : '';
                $d = isset($digits[$i]) ? (string) $digits[$i] : '';

                // group_name: only letters and exact length = $tc
                $gTrim = trim($g);
                if ($gTrim === '' || preg_match('/^[A-Za-z]+$/', $gTrim) !== 1 || strlen($gTrim) !== $tc) {
                    $validator->errors()->add("group_name.$i", "Group Name must be exactly {$tc} letter(s).");
                } else {
                    $gKey = strtolower($gTrim);
                    if (!isset($seenGroupNames[$tc])) {
                        $seenGroupNames[$tc] = [];
                    }
                    if ($tc !== 3) {
                        if (array_key_exists($gKey, $seenGroupNames[$tc])) {
                            $firstIndex = $seenGroupNames[$tc][$gKey];
                            $duplicateGroupIndexes[$firstIndex] = true;
                            $duplicateGroupIndexes[$i] = true;
                        } else {
                            $seenGroupNames[$tc][$gKey] = $i;
                        }
                    }
                }

                // digit: numeric and exact digits length = $tc
                $dTrim = trim($d);
                if ($dTrim === '' || preg_match('/^\\d+$/', $dTrim) !== 1 || strlen($dTrim) !== $tc) {
                    $validator->errors()->add("digit.$i", "Digit must be exactly {$tc} digit(s).");
                }

                if ($tc === 3) {
                    if (trim((string) ($tickets[$i] ?? '')) === '') {
                        $validator->errors()->add("ticket_amt.$i", 'Ticket Amount is required.');
                    }

                    if (trim((string) ($firstPrices[$i] ?? '')) === '') {
                        $validator->errors()->add("first_price.$i", 'First Price is required.');
                    }

                    if (trim((string) ($secondPrices[$i] ?? '')) === '') {
                        $validator->errors()->add("second_price.$i", 'Second Price is required.');
                    }

                    if (trim((string) ($thirdPrices[$i] ?? '')) === '') {
                        $validator->errors()->add("third_price.$i", 'Third Price is required.');
                    }
                }
            }

            foreach (array_keys($duplicateGroupIndexes) as $idx) {
                $tc = (int) ($tcs[$idx] ?? 0);
                if (in_array($tc, [1, 2, 3, 4, 5], true)) {
                    $validator->errors()->add("group_name.$idx", "Duplicate Group Name for {$tc}-digit items.");
                } else {
                    $validator->errors()->add("group_name.$idx", 'Duplicate Group Name.');
                }
            }

            $selectedTitles = array_filter(array_map('intval', explode(',', (string) $request->input('title', ''))));
            foreach ($selectedTitles as $title) {
                if ($title === 3 || !in_array($title, [1, 2, 4, 5], true)) {
                    continue;
                }

                if (trim((string) data_get($request->input('win_amount_by_title', []), $title, '')) === '') {
                    $validator->errors()->add("win_amount_by_title.$title", 'Win Amount is required.');
                }

                if (trim((string) data_get($request->input('ticket_amt_by_title', []), $title, '')) === '') {
                    $validator->errors()->add("ticket_amt_by_title.$title", 'Ticket Amount is required.');
                }
            }
        });

        $validated = $validator->validate();

        DB::transaction(function () use ($validated) {
            $slot = Slot::create([
               'main_title' => $validated['main_title'],
               'draw_date' => $validated['draw_date'],
                'booking_close_time' => $validated['booking_close_time'],
               'draw_time' => $validated['draw_time'],
                'short_title' => $validated['short_title'],
                'title' => $validated['title'],
                'commission' => $validated['commission'],
                'slug' => $this->makeUniqueSlug($validated['main_title']),
                'status' => 'Active',
            ]);

            $itemsCount = count($validated['group_name']);
            for ($i = 0; $i < $itemsCount; $i++) {
                $tc = (int) ($validated['item_title_count'][$i] ?? 0);
                $winAmount = $tc === 3 ? null : data_get($validated, "win_amount_by_title.{$tc}", data_get($validated, "win_amount.$i"));
                $ticketAmt = $tc === 3 ? $validated['ticket_amt'][$i] : data_get($validated, "ticket_amt_by_title.{$tc}", data_get($validated, "ticket_amt.$i"));
                SlotItem::create([
                    'slot_id' => $slot->slot_id,
                    'title' => $tc,
                    'group_name' => $validated['group_name'][$i],
                    'digit' => $validated['digit'][$i],
                    'color' => $validated['color'][$i] ?? null,
                    'win_amount' => $winAmount,
                    'ticket_amt' => $ticketAmt,
                    'first_price' => $validated['first_price'][$i] ?? null,
                    'second_price' => $validated['second_price'][$i] ?? null,
                    'third_price' => $validated['third_price'][$i] ?? null,
                ]);
            }
        });

        session()->flash('success', 'Slot created successfully.');

        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()
                ->route('admin.slots.index')
                ->with('success', 'Slot created successfully.');
        }

        return response()->json([
            'status' => true,
            'message' => 'Slot created successfully.',
        ]);
    }

    public function update(Request $request, $slug)
    {
        $slot = Slot::with('items')->where('slug', $slug)->firstOrFail();

        if ($request->isMethod('get')) {
            return view('slots.add', compact('slot'));
        }

        $this->normalizeMoneyInputs($request);
        $this->normalizeNumericCommaTitle($request);
        $this->normalizeSlotItems($request);

        $validator = Validator::make($request->all(), [
            'main_title' => ['required', 'string', 'max:255'],
            'draw_time' => ['required',],
            'draw_date' => ['required', 'date'],
            'booking_close_time' => ['required'],
            'short_title' => ['required', 'string', 'max:255'],
            'commission' => ['required', 'numeric', 'min:0', 'max:100'],
            'title' => ['required', 'string', 'max:255', 'regex:/^\\d+(?:,\\d+)*$/'],
            'win_amount' => ['nullable', 'array'],
            'win_amount.*' => ['nullable', 'numeric', 'min:0'],
            'ticket_amt' => ['required', 'array', 'min:1'],
            'ticket_amt.*' => ['nullable', 'numeric', 'min:0'],
            'first_price' => ['nullable', 'array'],
            'first_price.*' => ['nullable', 'numeric', 'min:0'],
            'second_price' => ['nullable', 'array'],
            'second_price.*' => ['nullable', 'numeric', 'min:0'],
            'third_price' => ['nullable', 'array'],
            'third_price.*' => ['nullable', 'numeric', 'min:0'],
            'win_amount_by_title' => ['nullable', 'array'],
            'win_amount_by_title.*' => ['nullable', 'numeric', 'min:0'],
            'ticket_amt_by_title' => ['nullable', 'array'],
            'ticket_amt_by_title.*' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:Active,Inactive'],
            'slot_item_id' => ['nullable', 'array'],
            'slot_item_id.*' => ['nullable'],
            'group_name' => ['required', 'array', 'min:1'],
            'group_name.*' => ['required', 'string', 'max:255'],
            'digit' => ['required', 'array', 'min:1'],
            'digit.*' => ['required'],
            'item_title_count' => ['required', 'array', 'min:1'],
            'item_title_count.*' => ['required', 'integer', 'in:1,2,3,4,5'],
            'color' => ['nullable', 'array'],
            'color.*' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $groups = $request->input('group_name', []);
            $digits = $request->input('digit', []);
            $tcs = $request->input('item_title_count', []);
            $tickets = $request->input('ticket_amt', []);
            $firstPrices = $request->input('first_price', []);
            $secondPrices = $request->input('second_price', []);
            $thirdPrices = $request->input('third_price', []);

            $count = max(count($groups), count($digits), count($tcs));
            $seenGroupNames = [];
            $duplicateGroupIndexes = [];
            for ($i = 0; $i < $count; $i++) {
                $tc = (int) ($tcs[$i] ?? 0);
                if (!in_array($tc, [1, 2, 3, 4, 5], true)) {
                    $validator->errors()->add("item_title_count.$i", 'Invalid title count.');
                    continue;
                }

                $g = isset($groups[$i]) ? (string) $groups[$i] : '';
                $d = isset($digits[$i]) ? (string) $digits[$i] : '';

                $gTrim = trim($g);
                if ($gTrim === '' || preg_match('/^[A-Za-z]+$/', $gTrim) !== 1 || strlen($gTrim) !== $tc) {
                    $validator->errors()->add("group_name.$i", "Group Name must be exactly {$tc} letter(s).");
                } else {
                    $gKey = strtolower($gTrim);
                    if (!isset($seenGroupNames[$tc])) {
                        $seenGroupNames[$tc] = [];
                    }
                    if ($tc !== 3) {
                        if (array_key_exists($gKey, $seenGroupNames[$tc])) {
                            $firstIndex = $seenGroupNames[$tc][$gKey];
                            $duplicateGroupIndexes[$firstIndex] = true;
                            $duplicateGroupIndexes[$i] = true;
                        } else {
                            $seenGroupNames[$tc][$gKey] = $i;
                        }
                    }
                }

                $dTrim = trim($d);
                if ($dTrim === '' || preg_match('/^\\d+$/', $dTrim) !== 1 || strlen($dTrim) !== $tc) {
                    $validator->errors()->add("digit.$i", "Digit must be exactly {$tc} digit(s).");
                }

                if ($tc === 3) {
                    if (trim((string) ($tickets[$i] ?? '')) === '') {
                        $validator->errors()->add("ticket_amt.$i", 'Ticket Amount is required.');
                    }

                    if (trim((string) ($firstPrices[$i] ?? '')) === '') {
                        $validator->errors()->add("first_price.$i", 'First Price is required.');
                    }

                    if (trim((string) ($secondPrices[$i] ?? '')) === '') {
                        $validator->errors()->add("second_price.$i", 'Second Price is required.');
                    }

                    if (trim((string) ($thirdPrices[$i] ?? '')) === '') {
                        $validator->errors()->add("third_price.$i", 'Third Price is required.');
                    }
                }
            }

            foreach (array_keys($duplicateGroupIndexes) as $idx) {
                $tc = (int) ($tcs[$idx] ?? 0);
                if (in_array($tc, [1, 2, 3, 4, 5], true)) {
                    $validator->errors()->add("group_name.$idx", "Duplicate Group Name for {$tc}-digit items.");
                } else {
                    $validator->errors()->add("group_name.$idx", 'Duplicate Group Name.');
                }
            }

            $selectedTitles = array_filter(array_map('intval', explode(',', (string) $request->input('title', ''))));
            foreach ($selectedTitles as $title) {
                if ($title === 3 || !in_array($title, [1, 2, 4, 5], true)) {
                    continue;
                }

                if (trim((string) data_get($request->input('win_amount_by_title', []), $title, '')) === '') {
                    $validator->errors()->add("win_amount_by_title.$title", 'Win Amount is required.');
                }

                if (trim((string) data_get($request->input('ticket_amt_by_title', []), $title, '')) === '') {
                    $validator->errors()->add("ticket_amt_by_title.$title", 'Ticket Amount is required.');
                }
            }
        });

        $validated = $validator->validate();

        DB::transaction(function () use ($validated, $slot) {
            $slot->update([
                'main_title' => $validated['main_title'],
                'draw_time' => $validated['draw_time'],
                'draw_date' => $validated['draw_date'],
                'booking_close_time' => $validated['booking_close_time'],
                'short_title' => $validated['short_title'],
                'title' => $validated['title'],
                'commission' => $validated['commission'],
                'slug' => $this->makeUniqueSlug($validated['main_title'], (int) $slot->slot_id),
                'status' => $validated['status'] ?? $slot->status,
            ]);

            $submittedIds = collect($validated['slot_item_id'] ?? [])
                ->filter(fn ($v) => !empty($v))
                ->map(fn ($v) => (int) $v)
                ->values()
                ->all();

            $itemsCount = count($validated['group_name']);
            $keptIds = [];
            for ($i = 0; $i < $itemsCount; $i++) {
                $itemId = $validated['slot_item_id'][$i] ?? null;
                $itemId = !empty($itemId) ? (int) $itemId : null;
                $tc = (int) ($validated['item_title_count'][$i] ?? 0);
                $winAmount = $tc === 3 ? null : data_get($validated, "win_amount_by_title.{$tc}", data_get($validated, "win_amount.$i"));
                $ticketAmt = $tc === 3 ? $validated['ticket_amt'][$i] : data_get($validated, "ticket_amt_by_title.{$tc}", data_get($validated, "ticket_amt.$i"));

                if ($itemId) {
                    $existing = SlotItem::withTrashed()
                        ->where('slot_id', $slot->slot_id)
                        ->where('slot_items_id', $itemId)
                        ->first();

                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        }

                        $existing->update([
                            'title' => $tc,
                            'group_name' => $validated['group_name'][$i],
                            'digit' => $validated['digit'][$i],
                            'color' => $validated['color'][$i] ?? null,
                            'win_amount' => $winAmount,
                            'ticket_amt' => $ticketAmt,
                            'first_price' => $validated['first_price'][$i] ?? null,
                            'second_price' => $validated['second_price'][$i] ?? null,
                            'third_price' => $validated['third_price'][$i] ?? null,
                        ]);

                        $keptIds[] = $existing->slot_items_id;
                        continue;
                    }
                }

                $created = SlotItem::create([
                    'slot_id' => $slot->slot_id,
                    'title' => $tc,
                    'group_name' => $validated['group_name'][$i],
                    'digit' => $validated['digit'][$i],
                    'color' => $validated['color'][$i] ?? null,
                    'win_amount' => $winAmount,
                    'ticket_amt' => $ticketAmt,
                    'first_price' => $validated['first_price'][$i] ?? null,
                    'second_price' => $validated['second_price'][$i] ?? null,
                    'third_price' => $validated['third_price'][$i] ?? null,
                ]);
                $keptIds[] = $created->slot_items_id;
            }

            SlotItem::where('slot_id', $slot->slot_id)
                ->whereNotIn('slot_items_id', $keptIds)
                ->delete();
        });

        session()->flash('success', 'Slot updated successfully.');

        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()
                ->route('admin.slots.index')
                ->with('success', 'Slot updated successfully.');
        }

        return response()->json([
            'status' => true,
            'message' => 'Slot updated successfully.',
        ]);
    }

    public function delete(Request $request, $slug)
    {
        $slot = Slot::with('items')->where('slug', $slug)->firstOrFail();

        DB::transaction(function () use ($slot) {
            $slot->items()->delete();
            $slot->delete();
        });

        if ($request->expectsJson() || $request->ajax()) {
            session()->flash('success', 'Slot deleted successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Slot deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.slots.index')
            ->with('success', 'Slot deleted successfully.');
    }

    public function deleteItem(Request $request, $id)
    {
        $item = SlotItem::where('slot_items_id', $id)->firstOrFail();
        $item->delete();

        if ($request->expectsJson() || $request->ajax()) {
            session()->flash('success', 'Slot item removed successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Slot item removed successfully.',
            ]);
        }



        // return response()->json([
        //     'status' => true,
        //     'message' => 'Slot item removed successfully.',
        // ]);
    }
}
