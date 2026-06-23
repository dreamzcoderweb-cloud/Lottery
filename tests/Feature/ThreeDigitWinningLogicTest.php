<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Slot;
use App\Models\SlotItem;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ThreeDigitWinningLogicTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $slot;
    protected $slotItem;

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

        // Create customer
        $this->customer = Customer::create([
            'name' => 'John Doe',
            'mobile' => '1234567890',
            'password' => bcrypt('password123'),
        ]);

        // Setup customer wallet with initial balance
        WalletRecharge::create([
            'customer_id' => $this->customer->customer_id,
            'balance' => 1000.00,
        ]);

        // Create slot draw date in past so result check executes immediately
        $this->slot = Slot::create([
            'main_title' => 'Test 3-Digit Slot',
            'draw_date' => now('Asia/Kolkata')->subDays(1)->format('Y-m-d'),
            'booking_close_time' => '12:00:00',
            'draw_time' => '12:30:00',
            'short_title' => 'T3D',
            'title' => '3',
            'slug' => 'test-3d-slot',
            'status' => 'Active',
        ]);

        // Create winning slot item for 3-digit (title = 3)
        $this->slotItem = SlotItem::create([
            'slot_id' => $this->slot->slot_id,
            'title' => 3,
            'group_name' => 'ABC',
            'digit' => 123,
            'color' => 'RED',
            'first_price' => 3000.00,
            'second_price' => 2000.00,
            'third_price' => 1000.00,
            'ticket_amt' => 200.00,
        ]);
    }

    public function test_first_prize_logic_matches_all_three_digits(): void
    {
        Sanctum::actingAs($this->customer);

        // Booking with complete 3-digit match (123)
        $booking = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 123,
            'qty' => 2,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');

        $response->assertStatus(200);

        // Verify total win amount is first_price (3000) * qty (2) = 6000
        $response->assertJsonPath('total_win_amount', 6000);
        $response->assertJsonFragment([
            'booking_id' => $booking->booking_id,
            'single_win_amount' => 3000,
            'win_amount' => 6000,
        ]);

        // Verify DB update
        $booking->refresh();
        $this->assertEquals('true', $booking->is_winner);
        $this->assertEquals(6000, $booking->win_amount);
        $this->assertEquals('true', $booking->first_price_flag);

        // Verify Wallet balance is 1000 (initial) + 6000 = 7000
        $wallet = WalletRecharge::where('customer_id', $this->customer->customer_id)->first();
        $this->assertEquals(7000, $wallet->balance);

        // Verify Wallet transaction created
        $this->assertDatabaseHas('wallet_transactions', [
            'customer_id' => $this->customer->customer_id,
            'type' => 'credit',
            'amount' => 6000,
            'payment_method' => 'slot win',
            'reference_no' => 'WIN-' . $booking->booking_id,
        ]);
    }

    public function test_second_prize_logic_matches_last_two_digits(): void
    {
        Sanctum::actingAs($this->customer);

        // Booking with last 2 digits matching (523 vs winning 123)
        $booking = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 523,
            'qty' => 1,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');

        $response->assertStatus(200);

        // Verify total win amount is second_price (2000) * qty (1) = 2000
        $response->assertJsonPath('total_win_amount', 2000);
        $response->assertJsonFragment([
            'booking_id' => $booking->booking_id,
            'single_win_amount' => 2000,
            'win_amount' => 2000,
        ]);

        // Verify DB update
        $booking->refresh();
        $this->assertEquals('true', $booking->is_winner);
        $this->assertEquals(2000, $booking->win_amount);
        $this->assertEquals('true', $booking->second_price_flag);
    }

    public function test_third_prize_logic_matches_last_digit(): void
    {
        Sanctum::actingAs($this->customer);

        // Booking with last digit matching (563 vs winning 123)
        $booking = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 563,
            'qty' => 3,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');

        $response->assertStatus(200);

        // Verify total win amount is third_price (1000) * qty (3) = 3000
        $response->assertJsonPath('total_win_amount', 3000);
        $response->assertJsonFragment([
            'booking_id' => $booking->booking_id,
            'single_win_amount' => 1000,
            'win_amount' => 3000,
        ]);

        // Verify DB update
        $booking->refresh();
        $this->assertEquals('true', $booking->is_winner);
        $this->assertEquals(3000, $booking->win_amount);
        $this->assertEquals('true', $booking->third_price_flag);
    }

    public function test_loss_logic_no_digits_matching(): void
    {
        Sanctum::actingAs($this->customer);

        // Booking with no digits matching last one (567 vs winning 123)
        $booking = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 567,
            'qty' => 1,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');

        $response->assertStatus(200);

        // Verify total win amount is 0
        $response->assertJsonPath('total_win_amount', 0);

        // Verify DB update
        $booking->refresh();
        $this->assertEquals('false', $booking->is_winner);
        $this->assertEquals(0, $booking->win_amount);

        // Verify Wallet transaction created for debit loss
        $this->assertDatabaseHas('wallet_transactions', [
            'customer_id' => $this->customer->customer_id,
            'type' => 'debit',
            'amount' => 0,
            'payment_method' => 'slot_loss',
            'reference_no' => 'LOSE-' . $booking->booking_id,
        ]);
    }

    public function test_other_title_ids_keep_existing_exact_match_behavior(): void
    {
        Sanctum::actingAs($this->customer);

        // Create slot for title_id = 1
        $slot1 = Slot::create([
            'main_title' => 'Test 1-Digit Slot',
            'draw_date' => now('Asia/Kolkata')->subDays(1)->format('Y-m-d'),
            'booking_close_time' => '12:00:00',
            'draw_time' => '12:30:00',
            'short_title' => 'T1D',
            'title' => '1',
            'slug' => 'test-1d-slot',
            'status' => 'Active',
        ]);

        // Create slot item with win_amount
        $slotItem1 = SlotItem::create([
            'slot_id' => $slot1->slot_id,
            'title' => 1,
            'group_name' => 'A',
            'digit' => 5,
            'win_amount' => 500.00,
            'ticket_amt' => 50.00,
        ]);

        // Booking with digit 5 (exact match)
        $booking1 = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $slot1->slot_id,
            'slot_items_id' => $slotItem1->slot_items_id,
            'title_id' => 1,
            'digits' => 5,
            'qty' => 1,
            'amount' => 50.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // Booking with digit 3 (mismatch)
        $booking2 = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $slot1->slot_id,
            'slot_items_id' => $slotItem1->slot_items_id,
            'title_id' => 1,
            'digits' => 3,
            'qty' => 1,
            'amount' => 50.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');

        $response->assertStatus(200);

        // Verify exact match booking wins (500.00)
        $booking1->refresh();
        $this->assertEquals('true', $booking1->is_winner);
        $this->assertEquals(500.00, $booking1->win_amount);

        // Verify mismatch booking loses (0.00)
        $booking2->refresh();
        $this->assertEquals('false', $booking2->is_winner);
        $this->assertEquals(0.00, $booking2->win_amount);
    }

    public function test_three_digit_price_flags_are_assigned_for_first_three_winners(): void
    {
        Sanctum::actingAs($this->customer);

        // Booking 1: Wins first price (123)
        $booking1 = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 123,
            'qty' => 1,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // Booking 2: Wins second price (523)
        $booking2 = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 523,
            'qty' => 1,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // Booking 3: Wins third price (563)
        $booking3 = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 563,
            'qty' => 1,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        // Booking 4: Wins third price (563) - 4th winner (no flag)
        $booking4 = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 563,
            'qty' => 1,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');
        $response->assertStatus(200);

        $winners = $response->json('winners');
        $this->assertCount(4, $winners);

        // Booking 1 should have first_price_flag
        $this->assertEquals($booking1->booking_id, $winners[0]['booking_id']);
        $this->assertTrue($winners[0]['first_price_flag'] ?? false);
        $this->assertArrayNotHasKey('second_price_flag', $winners[0]);
        $this->assertArrayNotHasKey('third_price_flag', $winners[0]);

        // Booking 2 should have second_price_flag
        $this->assertEquals($booking2->booking_id, $winners[1]['booking_id']);
        $this->assertTrue($winners[1]['second_price_flag'] ?? false);
        $this->assertArrayNotHasKey('first_price_flag', $winners[1]);
        $this->assertArrayNotHasKey('third_price_flag', $winners[1]);

        // Booking 3 should have third_price_flag
        $this->assertEquals($booking3->booking_id, $winners[2]['booking_id']);
        $this->assertTrue($winners[2]['third_price_flag'] ?? false);
        $this->assertArrayNotHasKey('first_price_flag', $winners[2]);
        $this->assertArrayNotHasKey('second_price_flag', $winners[2]);

        // Booking 4 should have no price flags
        $this->assertEquals($booking4->booking_id, $winners[3]['booking_id']);
        $this->assertArrayNotHasKey('first_price_flag', $winners[3]);
        $this->assertArrayNotHasKey('second_price_flag', $winners[3]);
        $this->assertArrayNotHasKey('third_price_flag', $winners[3]);
    }

    public function test_commission_deductions_from_winnings(): void
    {
        Sanctum::actingAs($this->customer);

        // Update slot commission to 10%
        $this->slot->update(['commission' => 10.00]);

        // Get initial wallet balance
        $wallet = WalletRecharge::where('customer_id', $this->customer->customer_id)->first();
        $initialBalance = (float) $wallet->balance;

        // Booking with complete 3-digit match (123)
        // first_price = 3000.00, qty = 2, so win_amount = 6000.00
        $booking = Booking::create([
            'customer_id' => $this->customer->customer_id,
            'slot_id' => $this->slot->slot_id,
            'slot_items_id' => $this->slotItem->slot_items_id,
            'title_id' => 3,
            'digits' => 123,
            'qty' => 2,
            'amount' => 200.00,
            'status' => 'success',
            'booking_time' => '10:00:00',
            'close_time' => '12:00:00',
            'payment_status' => 'paid',
        ]);

        $response = $this->getJson('/api/v1/tickets/result');
        $response->assertStatus(200);

        // Win Amount = 6000.00
        // Commission = 10% of 6000.00 = 600.00
        // Adjusted balance to credit = 6000.00 - 600.00 = 5400.00
        $expectedBalance = $initialBalance + 5400.00;

        $wallet->refresh();
        $this->assertEquals($expectedBalance, (float) $wallet->balance);

        // Verify transaction is recorded with adjusted amount
        $this->assertDatabaseHas('wallet_transactions', [
            'customer_id' => $this->customer->customer_id,
            'type' => 'credit',
            'amount' => 5400.00,
            'reference_no' => 'WIN-' . $booking->booking_id,
        ]);
    }
}
