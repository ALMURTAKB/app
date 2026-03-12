<?php

namespace App\Services;

use App\Events\MoneyReceived;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function sendMoney(User $sender, User $receiver, float $amount, ?string $note = null): Transaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero.');
        }

        $senderWallet = $sender->getOrCreateWallet()->fresh();
        $receiverWallet = $receiver->getOrCreateWallet()->fresh();

        if (! $senderWallet->hasSufficientBalance($amount)) {
            throw new \RuntimeException('Insufficient balance.');
        }

        return DB::transaction(function () use ($sender, $receiver, $senderWallet, $receiverWallet, $amount, $note) {
            $transaction = Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
                'currency' => $senderWallet->currency,
                'status' => 'pending',
                'note' => $note,
            ]);

            $senderWallet->withdraw($amount);
            $receiverWallet->deposit($amount);

            $transaction->update(['status' => 'completed']);

            event(new MoneyReceived($transaction->fresh(['sender', 'receiver'])));

            return $transaction;
        });
    }

    public function topUp(User $user, float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero.');
        }

        $wallet = $user->getOrCreateWallet();
        $wallet->deposit($amount);
    }
}
