<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Slot;
use App\Models\SlotItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SlotWinningReportFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (\Illuminate\Support\Facades\Schema::hasTable('slot_items') && !\Illuminate\Support\Facades\Schema::hasColumn('slot_items', 'title')) {
            \Illuminate\Support\Facades\Schema::table('slot_items', function ($table) {
                $table->integer('title')->nullable();
            });
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('bookings') && !\Illuminate\Support\Facades\Schema::hasColumn('bookings', 'digits')) {
            \Illuminate\Support\Facades\Schema::table('bookings', function ($table) {
                $table->integer('digits')->nullable();
            });
        }
    }

    public function test_slot_winning_report_returns_all_slots_for_today_and_yesterday(): void
    {
        $tz = 'Asia/Kolkata';
        $today = now($tz)->toDateString();
        $yesterday = now($tz)->subDay()->toDateString();
        $twoDaysAgo = now($tz)->subDays(2)->toDateString();

        $customer = Customer::create([
            'name' => 'Test User',
            'mobile' => '1234567890',
            'password' => bcrypt('password'),
        ]);

        Sanctum::actingAs($customer);

        // 1. Slot A: Yesterday's slot, NO bookings. (Should be returned now, previously was excluded)
        $slotA = Slot::create([
            'main_title' => 'Yesterday Slot',
            'draw_date' => $yesterday,
            'booking_close_time' => '12:00:00',
            'draw_time' => '12:30:00',
            'short_title' => 'YES',
            'title' => '3',
            'slug' => 'yesterday-slot',
            'status' => 'Active',
        ]);
        SlotItem::create([
            'slot_id' => $slotA->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 123,
            'ticket_amt' => 10.00,
            'win_amount' => 500.00,
        ]);

        // 2. Slot B: Today's slot, closed, NO bookings. (Should be returned)
        $slotB = Slot::create([
            'main_title' => 'Today Slot Closed',
            'draw_date' => $today,
            'booking_close_time' => '00:00:00', // definitely closed
            'draw_time' => '00:30:00',
            'short_title' => 'TOD',
            'title' => '3',
            'slug' => 'today-slot-closed',
            'status' => 'Active',
        ]);
        SlotItem::create([
            'slot_id' => $slotB->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 456,
            'ticket_amt' => 10.00,
            'win_amount' => 500.00,
        ]);

        // 3. Slot C: Today's slot, NOT closed yet. (Should NOT be returned because of close time check)
        $slotC = Slot::create([
            'main_title' => 'Today Slot Open',
            'draw_date' => $today,
            'booking_close_time' => '23:59:59', // definitely open
            'draw_time' => '23:59:59',
            'short_title' => 'TDO',
            'title' => '3',
            'slug' => 'today-slot-open',
            'status' => 'Active',
        ]);
        SlotItem::create([
            'slot_id' => $slotC->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 789,
            'ticket_amt' => 10.00,
            'win_amount' => 500.00,
        ]);

        // 4. Slot D: Older slot (2 days ago), has bookings with is_winner. (Should be returned)
        $slotD = Slot::create([
            'main_title' => 'Older Slot With Bookings',
            'draw_date' => $twoDaysAgo,
            'booking_close_time' => '12:00:00',
            'draw_time' => '12:30:00',
            'short_title' => 'OLD_W',
            'title' => '3',
            'slug' => 'older-slot-with-bookings',
            'status' => 'Active',
        ]);
        $slotItemD = SlotItem::create([
            'slot_id' => $slotD->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 111,
            'ticket_amt' => 10.00,
            'win_amount' => 500.00,
        ]);
        Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slotD->slot_id,
            'slot_items_id' => $slotItemD->slot_items_id,
            'title_id' => 3,
            'digits' => 111,
            'qty' => 1,
            'amount' => 10.00,
            'status' => 'success',
            'is_winner' => 1,
            'win_amount' => 500.00,
            'booking_time' => now()->subDays(2)->toDateTimeString(),
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // 5. Slot E: Older slot (2 days ago), NO bookings. (Should NOT be returned because older than yesterday and has no bookings)
        $slotE = Slot::create([
            'main_title' => 'Older Slot No Bookings',
            'draw_date' => $twoDaysAgo,
            'booking_close_time' => '12:00:00',
            'draw_time' => '12:30:00',
            'short_title' => 'OLD_N',
            'title' => '3',
            'slug' => 'older-slot-no-bookings',
            'status' => 'Active',
        ]);
        SlotItem::create([
            'slot_id' => $slotE->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 222,
            'ticket_amt' => 10.00,
            'win_amount' => 500.00,
        ]);

        $response = $this->getJson('/api/v1/reports/winning-slots');

        $response->assertOk();
        
        $data = $response->json('data');
        $slotIds = collect($data)->pluck('slot_id')->all();

        // Slot A (yesterday, no bookings) should be returned
        $this->assertContains($slotA->slot_id, $slotIds);

        // Slot B (today, closed, no bookings) should be returned
        $this->assertContains($slotB->slot_id, $slotIds);

        // Slot C (today, not closed) should NOT be returned
        $this->assertNotContains($slotC->slot_id, $slotIds);

        // Slot D (older, with bookings) should be returned
        $this->assertContains($slotD->slot_id, $slotIds);

        // Slot E (older, no bookings) should NOT be returned
        $this->assertNotContains($slotE->slot_id, $slotIds);
    }
}
