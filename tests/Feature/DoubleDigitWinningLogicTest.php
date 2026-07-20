<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Slot;
use App\Models\SlotItem;
use App\Models\WalletRecharge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoubleDigitWinningLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_double_digit_exact_match_is_winner(): void
    {
        $customer = Customer::factory()->create();
        $slot = Slot::create([
            'main_title' => 'Monday Slot',
            'draw_date' => now()->subDay()->format('Y-m-d'),
            'booking_close_time' => '10:00:00',
            'close_time' => '10:00:00',
            'commission' => 0,
        ]);

        $slotItem = SlotItem::create([
            'slot_id' => $slot->slot_id,
            'title' => 2, // Double Digit
            'group_name' => 'QA',
            'digit' => '00',
            'win_amount' => 20,
            'ticket_amt' => 15,
        ]);

        $booking = Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slot->slot_id,
            'slot_items_id' => $slotItem->slot_items_id,
            'title_id' => 2,
            'digits' => '00',
            'qty' => 1,
            'amount' => 15,
            'status' => 'success',
            'payment_status' => 'paid',
            'is_winner' => null,
        ]);

        WalletRecharge::create([
            'customer_id' => $customer->customer_id,
            'balance' => 100,
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/customer/booking-history');

        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('true', $booking->is_winner);
        $this->assertEquals(20, $booking->win_amount);
    }

    public function test_double_digit_single_zero_vs_double_zero_is_loser(): void
    {
        $customer = Customer::factory()->create();
        $slot = Slot::create([
            'main_title' => 'Monday Slot',
            'draw_date' => now()->subDay()->format('Y-m-d'),
            'booking_close_time' => '10:00:00',
            'close_time' => '10:00:00',
            'commission' => 0,
        ]);

        $slotItem = SlotItem::create([
            'slot_id' => $slot->slot_id,
            'title' => 2, // Double Digit
            'group_name' => 'QA',
            'digit' => '00',
            'win_amount' => 20,
            'ticket_amt' => 15,
        ]);

        // Customer enters '0' instead of '00'
        $booking = Booking::create([
            'customer_id' => $customer->customer_id,
            'slot_id' => $slot->slot_id,
            'slot_items_id' => $slotItem->slot_items_id,
            'title_id' => 2,
            'digits' => '0',
            'qty' => 1,
            'amount' => 15,
            'status' => 'success',
            'payment_status' => 'paid',
            'is_winner' => null,
        ]);

        WalletRecharge::create([
            'customer_id' => $customer->customer_id,
            'balance' => 100,
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson('/api/customer/booking-history');

        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('false', $booking->is_winner);
        $this->assertEquals(0, $booking->win_amount);
    }
}
