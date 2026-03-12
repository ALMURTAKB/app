<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class SendMoney extends Component
{
    #[Rule('required|email|exists:users,email')]
    public string $recipientEmail = '';

    #[Rule('required|numeric|min:0.01')]
    public float $amount = 0;

    #[Rule('nullable|string|max:255')]
    public string $note = '';

    public bool $success = false;

    public string $errorMessage = '';

    public function send(WalletService $walletService): void
    {
        $this->validate();
        $this->success = false;
        $this->errorMessage = '';

        $sender = Auth::user();
        $receiver = User::where('email', $this->recipientEmail)->first();

        if ($receiver->id === $sender->id) {
            $this->errorMessage = 'You cannot send money to yourself.';

            return;
        }

        try {
            $walletService->sendMoney($sender, $receiver, $this->amount, $this->note ?: null);

            $this->reset(['recipientEmail', 'amount', 'note']);
            $this->success = true;
            $this->dispatch('transaction-sent');
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.send-money');
    }
}
