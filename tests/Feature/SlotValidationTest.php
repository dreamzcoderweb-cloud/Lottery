<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotValidationTest extends TestCase
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
    }

    public function test_three_digits_slot_allows_duplicate_group_names(): void
    {
        $payload = [
            'main_title' => 'Test Three Digits Slot',
            'draw_date' => now()->addDays(2)->format('Y-m-d'),
            'draw_time' => '14:30',
            'booking_close_time' => '14:00',
            'short_title' => 'T3D',
            'title' => '3',
            'group_name' => ['ABC', 'ABC'], // Duplicate group names
            'digit' => ['123', '456'],
            'item_title_count' => [3, 3],
            'ticket_amt' => [10.00, 10.00],
            'first_price' => [1000.00, 1000.00],
            'second_price' => [500.00, 500.00],
            'third_price' => [250.00, 250.00],
            'color' => ['#000000', '#ffffff'],
        ];

        $response = $this->postJson(route('admin.slots.add'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Slot created successfully.',
            ]);

        $this->assertDatabaseHas('slots', [
            'main_title' => 'Test Three Digits Slot',
            'title' => '3',
        ]);

        $this->assertDatabaseHas('slot_items', [
            'group_name' => 'ABC',
            'digit' => 123,
            'win_amount' => null,
            'first_price' => 1000.00,
            'second_price' => 500.00,
            'third_price' => 250.00,
        ]);

        $this->assertDatabaseHas('slot_items', [
            'group_name' => 'ABC',
            'digit' => 456,
        ]);
    }

    public function test_two_digits_slot_does_not_allow_duplicate_group_names(): void
    {
        $payload = [
            'main_title' => 'Test Two Digits Slot',
            'draw_date' => now()->addDays(2)->format('Y-m-d'),
            'draw_time' => '14:30',
            'booking_close_time' => '14:00',
            'short_title' => 'T2D',
            'title' => '2',
            'group_name' => ['AB', 'AB'], // Duplicate group names
            'digit' => ['12', '34'],
            'item_title_count' => [2, 2],
            'win_amount_by_title' => [2 => 100.00],
            'ticket_amt_by_title' => [2 => 5.00],
            'win_amount' => ['', ''],
            'ticket_amt' => ['', ''],
            'color' => ['#000000', '#ffffff'],
        ];

        $response = $this->postJson(route('admin.slots.add'), $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['group_name.0', 'group_name.1']);
    }

    public function test_edit_slot_loads_more_than_three_items(): void
    {
        $slot = Slot::create([
            'main_title' => 'Kerala Lottery',
            'draw_date' => now()->addDays(2)->format('Y-m-d'),
            'draw_time' => '14:30:00',
            'booking_close_time' => '14:00:00',
            'short_title' => 'slot 1',
            'title' => '1,2,3,4',
            'slug' => 'kerala-lottery-3',
            'status' => 'Active',
        ]);

        for ($i = 0; $i < 4; $i++) {
            \App\Models\SlotItem::create([
                'slot_id' => $slot->slot_id,
                'title' => 1,
                'group_name' => chr(65 + $i), // A, B, C, D
                'digit' => $i + 1,
                'color' => '#000000',
                'win_amount' => 100.00,
                'ticket_amt' => 12.00,
            ]);
        }

        $response = $this->get(route('admin.slots.edit', $slot->slug));

        $response->assertStatus(200);

        $response->assertSee('value="A"', false);
        $response->assertSee('value="B"', false);
        $response->assertSee('value="C"', false);
        $response->assertSee('value="D"', false);
    }

    public function test_one_digit_slot_with_empty_prices_saves_successfully(): void
    {
        $payload = [
            'main_title' => 'Test One Digit Slot',
            'draw_date' => now()->addDays(2)->format('Y-m-d'),
            'draw_time' => '14:30',
            'booking_close_time' => '14:00',
            'short_title' => 'T1D',
            'title' => '1',
            'group_name' => ['A'],
            'digit' => ['1'],
            'item_title_count' => [1],
            'win_amount_by_title' => [1 => 100.00],
            'ticket_amt_by_title' => [1 => 10.00],
            'win_amount' => [''],
            'ticket_amt' => [''],
            'first_price' => [''], // Empty strings like submitted by the form
            'second_price' => [''],
            'third_price' => [''],
            'color' => ['#000000'],
        ];

        $response = $this->postJson(route('admin.slots.add'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Slot created successfully.',
            ]);

        $this->assertDatabaseHas('slots', [
            'main_title' => 'Test One Digit Slot',
            'title' => '1',
        ]);

        $this->assertDatabaseHas('slot_items', [
            'group_name' => 'A',
            'digit' => 1,
            'first_price' => null,
            'second_price' => null,
            'third_price' => null,
        ]);
    }
}

