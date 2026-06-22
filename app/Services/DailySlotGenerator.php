<?php

namespace App\Services;

use App\Models\Slot;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DailySlotGenerator
{
    public function generate(?CarbonInterface $targetDate = null, ?CarbonInterface $sourceDate = null): array
    {
        $targetDate = $this->dateOnly($targetDate ?? now('Asia/Kolkata'));
        $sourceDate = $this->dateOnly($sourceDate ?? $targetDate->copy()->subDay());

        return DB::transaction(function () use ($targetDate, $sourceDate) {
            if (Slot::withTrashed()->whereDate('draw_date', $targetDate->toDateString())->exists()) {
                return [
                    'created' => false,
                    'reason' => 'target_date_already_has_slots',
                    'source_date' => $sourceDate->toDateString(),
                    'target_date' => $targetDate->toDateString(),
                    'slots_created' => 0,
                    'items_created' => 0,
                ];
            }

            $sourceQuery = Slot::query()
                ->with(['items' => fn ($query) => $query->orderBy('slot_items_id')])
                ->whereDate('draw_date', $sourceDate->toDateString())
                ->orderBy('slot_id');

            if (!$sourceQuery->exists()) {
                return [
                    'created' => false,
                    'reason' => 'source_date_has_no_slots',
                    'source_date' => $sourceDate->toDateString(),
                    'target_date' => $targetDate->toDateString(),
                    'slots_created' => 0,
                    'items_created' => 0,
                ];
            }

            $slotsCreated = 0;
            $itemsCreated = 0;

            $sourceQuery->chunkById(100, function ($slots) use ($targetDate, &$slotsCreated, &$itemsCreated) {
                foreach ($slots as $sourceSlot) {
                    $newSlot = $sourceSlot->replicate(['draw_date', 'slug']);
                    $newSlot->draw_date = $targetDate->toDateString();
                    $newSlot->slug = $this->makeUniqueSlug($sourceSlot, $targetDate);
                    $newSlot->save();

                    $items = [];
                    $now = now();

                    foreach ($sourceSlot->items as $sourceItem) {
                        $item = $sourceItem->replicate(['slot_id']);
                        $attributes = $item->getAttributes();
                        unset($attributes['slot_items_id'], $attributes['created_at'], $attributes['updated_at'], $attributes['deleted_at']);

                        $attributes['slot_id'] = $newSlot->slot_id;
                        $attributes['created_at'] = $now;
                        $attributes['updated_at'] = $now;

                        $items[] = $attributes;
                    }

                    if (!empty($items)) {
                        $newSlot->items()->insert($items);
                        $itemsCreated += count($items);
                    }

                    $slotsCreated++;
                }
            }, 'slot_id', 'slot_id');

            return [
                'created' => true,
                'reason' => null,
                'source_date' => $sourceDate->toDateString(),
                'target_date' => $targetDate->toDateString(),
                'slots_created' => $slotsCreated,
                'items_created' => $itemsCreated,
            ];
        });
    }

    private function dateOnly(CarbonInterface $date): Carbon
    {
        return Carbon::instance($date->toDateTime())->setTimezone('Asia/Kolkata')->startOfDay();
    }

    private function makeUniqueSlug(Slot $sourceSlot, CarbonInterface $targetDate): string
    {
        $base = Str::slug($sourceSlot->main_title . '-' . $targetDate->toDateString());
        $base = $base !== '' ? $base : 'slot-' . $targetDate->format('Y-m-d');

        $slug = $base;
        $suffix = 1;

        while (Slot::withTrashed()->where('slug', $slug)->exists()) {
            $suffix++;
            $slug = $base . '-' . $suffix;
        }

        return $slug;
    }
}
