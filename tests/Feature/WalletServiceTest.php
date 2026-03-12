<?php

namespace Tests\Feature;

use App\Events\MoneyReceived;
use App\Models\Transaction;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $walletService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletService = new WalletService();
    }

    public function test_can_top_up_wallet(): void
    {
        $user = User::factory()->create();

        $this->walletService->topUp($user, 100.00);

        $this->assertEquals(100.00, $user->fresh()->wallet->balance);
    }

    public function test_cannot_top_up_with_zero_amount(): void
    {
        $user = User::factory()->create();

        $this->expectException(\InvalidArgumentException::class);

        $this->walletService->topUp($user, 0);
    }

    public function test_can_send_money(): void
    {
        Event::fake([MoneyReceived::class]);

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $this->walletService->topUp($sender, 200.00);

        $transaction = $this->walletService->sendMoney($sender, $receiver, 50.00, 'Test payment');

        $this->assertEquals(150.00, $sender->fresh()->wallet->balance);
        $this->assertEquals(50.00, $receiver->fresh()->wallet->balance);
        $this->assertEquals('completed', $transaction->status);
        $this->assertEquals(50.00, $transaction->amount);

        Event::assertDispatched(MoneyReceived::class);
    }

    public function test_cannot_send_money_with_insufficient_balance(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $this->walletService->topUp($sender, 10.00);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Insufficient balance.');

        $this->walletService->sendMoney($sender, $receiver, 50.00);
    }

    public function test_cannot_send_zero_amount(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $this->expectException(\InvalidArgumentException::class);

        $this->walletService->sendMoney($sender, $receiver, 0);
    }

    public function test_transaction_is_created_in_database(): void
    {
        Event::fake([MoneyReceived::class]);

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $this->walletService->topUp($sender, 100.00);
        $this->walletService->sendMoney($sender, $receiver, 25.00, 'Hello');

        $this->assertDatabaseHas('transactions', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => 25.00,
            'status' => 'completed',
            'note' => 'Hello',
        ]);
    }
}
