<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/v1/customer/register', [
            'name' => 'Test Customer',
            'mobile' => '9999999999',
            'password' => 'secret123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'token', 'customer' => ['customer_id', 'name', 'mobile']]);

        $this->assertDatabaseHas('customers', ['mobile' => '9999999999']);
    }

    public function test_customer_can_login_and_receive_token(): void
    {
        Customer::create([
            'name' => 'Test Customer',
            'mobile' => '8888888888',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/v1/customer/login', [
            'mobile' => '8888888888',
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message', 'token', 'customer' => ['customer_id', 'name', 'mobile']]);
    }

    public function test_customer_can_access_me_when_authenticated(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'mobile' => '7777777777',
            'password' => Hash::make('secret123'),
        ]);

        Sanctum::actingAs($customer);

        $this->getJson('/api/v1/customer/me')
            ->assertOk()
            ->assertJsonPath('customer.customer_id', $customer->customer_id);
    }

    public function test_customer_can_logout_and_token_is_revoked(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'mobile' => '6666666666',
            'password' => Hash::make('secret123'),
        ]);

        Sanctum::actingAs($customer);

        $this->postJson('/api/v1/customer/logout')
            ->assertOk()
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertCount(0, $customer->tokens()->get());
    }
}

