<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Slot;
use App\Models\SlotItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WinningsSlotsReportGroupingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();

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

    public function test_winning_tickets_are_grouped_and_quantities_summed_on_slot_details(): void
    {
        // 1. Create a customer
        $customer = Customer::create([
            'name' => 'Jane Doe',
            'mobile' => '9876543210',
            'password' => bcrypt('password123'),
        ]);

        // 2. Create a slot
        $slot = Slot::create([
            'main_title' => 'Weekly Mega Draw',
            'draw_date' => now('Asia/Kolkata')->subDays(1)->format('Y-m-d'),
            'booking_close_time' => '12:00:00',
            'draw_time' => '12:30:00',
            'short_title' => 'WMD',
            'title' => '3',
            'slug' => 'weekly-mega-draw',
            'status' => 'Active',
        ]);

        // 3. Create three-digit slot items with different ticket amounts
        $slotItem200 = SlotItem::create([
            'slot_id' => $slot->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 123,
            'color' => '#000000',
            'first_price' => 3000.00,
            'second_price' => 2000.00,
            'third_price' => 1000.00,
            'ticket_amt' => 200.00,
        ]);

        $slotItem300 = SlotItem::create([
            'slot_id' => $slot->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 123,
            'color' => '#000000',
            'first_price' => 5000.00,
            'second_price' => 3000.00,
            'third_price' => 2000.00,
            'ticket_amt' => 300.00,
        ]);

        // Create a single-digit slot item for group 'A' and digit 7 (title = 1)
        $singleDigitItem = SlotItem::create([
            'slot_id' => $slot->slot_id,
            'title' => 1,
            'group_name' => 'A',
            'digit' => 7,
            'color' => '#000000',
            'win_amount' => 500.00,
            'ticket_amt' => 10.00,
        ]);

        // 4. Create winning bookings for each item
        Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slot->slot_id,
            'slot_items_id' => $singleDigitItem->slot_items_id,
            'title_id' => 1,
            'digits' => 7,
            'qty' => 2,
            'amount' => 10.00,
            'status' => 'success',
            'is_winner' => 'true',
            'win_amount' => 500.00,
            'booking_time' => now()->subHours(2)->toDateTimeString(),
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slot->slot_id,
            'slot_items_id' => $slotItem200->slot_items_id,
            'title_id' => 3,
            'digits' => 123,
            'qty' => 2,
            'amount' => 200.00,
            'status' => 'success',
            'is_winner' => 'true',
            'win_amount' => 3000.00,
            'booking_time' => now()->subHours(2)->toDateTimeString(),
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slot->slot_id,
            'slot_items_id' => $slotItem300->slot_items_id,
            'title_id' => 3,
            'digits' => 123,
            'qty' => 3,
            'amount' => 300.00,
            'status' => 'success',
            'is_winner' => 'true',
            'win_amount' => 5000.00,
            'booking_time' => now()->subHours(2)->toDateTimeString(),
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // Create a losing booking for slotItem200 (200.00 ticket_amt)
        Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slot->slot_id,
            'slot_items_id' => $slotItem200->slot_items_id,
            'title_id' => 3,
            'digits' => 123,
            'qty' => 5,
            'amount' => 1000.00, // 5 * 200
            'status' => 'success',
            'is_winner' => 'false',
            'win_amount' => 0.00,
            'booking_time' => now()->subHours(2)->toDateTimeString(),
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // 5. Request the slot details admin report page
        $response = $this->get(route('admin.reports.slot-details', ['slot_id' => $slot->slot_id]));

        // 6. Assert response success
        $response->assertStatus(200);

        // 7. Verify that Winning Groups table items (group names) are displayed
        $response->assertSee('A');
        $response->assertSee('ABC');

        // 8. Verify Win Amount column is present, and single digit item (7) shows win amount (500.00)
        $response->assertSee('Win Amount');
        $response->assertSee('₹500.00');

        // 9. Verify first_price, second_price, third_price columns and values are displayed
        $response->assertSee('First Prize');
        $response->assertSee('Second Prize');
        $response->assertSee('Third Prize');
        $response->assertSee('₹3,000.00');
        $response->assertSee('₹5,000.00');

        // 10. Verify that ticket tables are NOT displayed on the main slot details page
        $response->assertDontSee('Winning Tickets (');
        $response->assertDontSee('Lose Tickets (');

        // 11. Verify that the "View Tickets" button is displayed on the slot details page
        $response->assertSee('View Tickets');
        $response->assertSee(route('admin.reports.slot-tickets', ['slot_id' => $slot->slot_id]));

        // 12. Request the new separate tickets page
        $ticketsResponse = $this->get(route('admin.reports.slot-tickets', ['slot_id' => $slot->slot_id]));
        $ticketsResponse->assertStatus(200);

        // 13. Verify that the ticket table and headers are displayed on the tickets page
        $ticketsResponse->assertSee('A (10)');
        $ticketsResponse->assertSee('ABC (300)');
        $ticketsResponse->assertSee('ABC (200)');
        $ticketsResponse->assertSee('Ticket Details (4)');
        $ticketsResponse->assertDontSee('Winning Tickets (3)');
        $ticketsResponse->assertDontSee('Lose Tickets (1)');
        $ticketsResponse->assertSee('Total Tickets:');
        $ticketsResponse->assertSee('Total Winners:');
        $ticketsResponse->assertSee('Total Losers:');
        $ticketsResponse->assertSee('Total Win Amount:');
        $ticketsResponse->assertSee('Total Amount Invested:');
        $ticketsResponse->assertSee('₹1,000.00');
        $ticketsResponse->assertSee('₹8,500.00');
    }
}
