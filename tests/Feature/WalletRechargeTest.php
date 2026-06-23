<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use App\Models\WalletRecharge;
use App\Models\WalletRechargeRequest;
use App\Models\WalletTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WalletRechargeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_customer_can_submit_wallet_recharge_request(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'mobile' => '9999999999',
            'password' => bcrypt('password123'),
        ]);

        Sanctum::actingAs($customer);

        $file = UploadedFile::fake()->create('receipt.png', 100, 'image/png');

        $response = $this->postJson('/api/v1/wallet-recharge/store', [
            'amount' => 500,
            'payment_method' => 'UPI',
            'payment_proof' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Wallet recharge request submitted successfully and is pending approval',
            ]);

        // Assert record is created with Pending status
        $this->assertDatabaseHas('wallet_recharge_requests', [
            'customer_id' => $customer->customer_id,
            'amount' => '500.00',
            'payment_method' => 'UPI',
            'status' => 'pending',
        ]);

        // Get the request and assert the file is stored
        $rechargeRequest = WalletRechargeRequest::first();
        $filePath = public_path('assets/img/payment_proofs/' . $rechargeRequest->payment_proof);
        $this->assertFileExists($filePath);
        @unlink($filePath);

        // Assert balance is NOT updated
        $wallet = WalletRecharge::where('customer_id', $customer->customer_id)->first();
        $this->assertNull($wallet);

        // Assert no transactions created
        $this->assertDatabaseMissing('wallet_transactions', [
            'customer_id' => $customer->customer_id,
        ]);
    }

    public function test_admin_can_approve_wallet_recharge_request(): void
    {
        $this->withoutMiddleware();

        $customer = Customer::create([
            'name' => 'Test Customer',
            'mobile' => '9999999999',
            'password' => bcrypt('password123'),
        ]);

        $rechargeRequest = WalletRechargeRequest::create([
            'customer_id' => $customer->customer_id,
            'amount' => 1000.00,
            'payment_method' => 'Bank Transfer',
            'payment_proof' => 'payment_proofs/fake.png',
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.recharges.approve', $rechargeRequest->wallet_recharge_request_id), [
            'remarks' => 'Verified payment proof, looks good',
        ]);

        $response->assertRedirect(route('admin.recharges.show', $rechargeRequest->wallet_recharge_request_id));

        // Assert request status updated to approved
        $this->assertDatabaseHas('wallet_recharge_requests', [
            'wallet_recharge_request_id' => $rechargeRequest->wallet_recharge_request_id,
            'status' => 'approved',
            'remarks' => 'Verified payment proof, looks good',
        ]);

        // Assert wallet balance updated
        $this->assertDatabaseHas('wallet_recharges', [
            'customer_id' => $customer->customer_id,
            'balance' => '1000.00',
        ]);

        // Assert transaction is created
        $this->assertDatabaseHas('wallet_transactions', [
            'customer_id' => $customer->customer_id,
            'type' => 'credit',
            'amount' => '1000.00',
            'payment_method' => 'Bank Transfer',
            'remarks' => 'Wallet Recharge',
        ]);
    }

    public function test_admin_can_reject_wallet_recharge_request(): void
    {
        $this->withoutMiddleware();

        $customer = Customer::create([
            'name' => 'Test Customer',
            'mobile' => '9999999999',
            'password' => bcrypt('password123'),
        ]);

        $rechargeRequest = WalletRechargeRequest::create([
            'customer_id' => $customer->customer_id,
            'amount' => 1000.00,
            'payment_method' => 'Bank Transfer',
            'payment_proof' => 'payment_proofs/fake.png',
            'status' => 'pending',
        ]);

        // Remarks are required for rejection
        $response = $this->post(route('admin.recharges.reject', $rechargeRequest->wallet_recharge_request_id), [
            'remarks' => 'Invalid transaction reference in proof',
        ]);

        $response->assertRedirect(route('admin.recharges.show', $rechargeRequest->wallet_recharge_request_id));

        // Assert request status updated to rejected
        $this->assertDatabaseHas('wallet_recharge_requests', [
            'wallet_recharge_request_id' => $rechargeRequest->wallet_recharge_request_id,
            'status' => 'rejected',
            'remarks' => 'Invalid transaction reference in proof',
        ]);

        // Assert wallet balance is NOT updated (should remain non-existent/0)
        $wallet = WalletRecharge::where('customer_id', $customer->customer_id)->first();
        $this->assertNull($wallet);

        // Assert no transaction is created
        $this->assertDatabaseMissing('wallet_transactions', [
            'customer_id' => $customer->customer_id,
        ]);
    }

    public function test_wallet_index_calculates_and_returns_commission_correctly(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'mobile' => '9999999999',
            'password' => bcrypt('password123'),
        ]);

        WalletRecharge::create([
            'customer_id' => $customer->customer_id,
            'balance' => 5000.00,
        ]);

        // Create transaction 1: Credit win 2700, Debit commission 300 (originally 3000 win, so 10%)
        WalletTransactions::create([
            'customer_id' => $customer->customer_id,
            'type' => 'credit',
            'amount' => 2700.00,
            'payment_method' => 'slot win',
            'reference_no' => 'WIN-101',
            'remarks' => 'Slot winning amount credited',
        ]);
        WalletTransactions::create([
            'customer_id' => $customer->customer_id,
            'type' => 'debit',
            'amount' => 300.00,
            'payment_method' => 'commission',
            'reference_no' => 'COM-101',
            'remarks' => 'Commission deducted from winnings',
        ]);

        // Create transaction 2: Credit win 1600, Debit commission 400 (originally 2000 win, so 20%)
        WalletTransactions::create([
            'customer_id' => $customer->customer_id,
            'type' => 'credit',
            'amount' => 1600.00,
            'payment_method' => 'slot win',
            'reference_no' => 'WIN-102',
            'remarks' => 'Slot winning amount credited',
        ]);
        WalletTransactions::create([
            'customer_id' => $customer->customer_id,
            'type' => 'debit',
            'amount' => 400.00,
            'payment_method' => 'commission',
            'reference_no' => 'COM-102',
            'remarks' => 'Commission deducted from winnings',
        ]);

        Sanctum::actingAs($customer);

        $response = $this->getJson('/api/v1/wallet-recharges');

        $response->assertStatus(200);

        // Verify total commission = 300 + 400 = 700
        $response->assertJsonPath('total_commission', 700);

        // Average commission percentage = (10% + 20%) / 2 = 15%
        $response->assertJsonPath('average_commission_percentage', 15);
        $response->assertJsonPath('commission_percentage', 15);

        // Verify individual transaction commission percentages and amounts in history
        $transactions = $response->json('transactions');
        $this->assertCount(4, $transactions); // 2 credits, 2 debits (commissions)

        foreach ($transactions as $tx) {
            if ($tx['reference_no'] === 'WIN-101' || $tx['reference_no'] === 'COM-101') {
                $this->assertEquals(10, $tx['commission_percentage']);
                $this->assertEquals(300, $tx['commission_amount']);
            } elseif ($tx['reference_no'] === 'WIN-102' || $tx['reference_no'] === 'COM-102') {
                $this->assertEquals(20, $tx['commission_percentage']);
                $this->assertEquals(400, $tx['commission_amount']);
            }
        }
    }
}
