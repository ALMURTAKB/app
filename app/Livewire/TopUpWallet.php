<?php

namespace App\Livewire;

use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TopUpWallet extends Component
{
    #[Rule('required|numeric|min:1')]
    public float $amount = 0;

    public bool $success = false;

    public string $errorMessage = '';

    public function topUp(WalletService $walletService): void
    {
        $this->validate();
        $this->success = false;
        $this->errorMessage = '';

        try {
            $walletService->topUp(Auth::user(), $this->amount);
            $this->reset('amount');
            $this->success = true;
            $this->dispatch('wallet-topped-up');
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.top-up-wallet');
    }
}
