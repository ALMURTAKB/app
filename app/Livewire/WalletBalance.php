<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WalletBalance extends Component
{
    public function getListeners(): array
    {
        return [
            'echo-private:user.' . Auth::id() . ',MoneyReceived' => 'refreshBalance',
        ];
    }

    public function refreshBalance(): void
    {
        Auth::user()->wallet?->refresh();
    }

    public function render()
    {
        $wallet = Auth::user()->getOrCreateWallet();

        return view('livewire.wallet-balance', compact('wallet'));
    }
}
