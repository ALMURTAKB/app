<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function getListeners(): array
    {
        return [
            'transaction-sent' => '$refresh',
            'echo-private:user.' . Auth::id() . ',MoneyReceived' => '$refresh',
        ];
    }

    public function render()
    {
        $userId = Auth::id();
        $query = Transaction::with(['sender', 'receiver'])
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            });

        if ($this->filter === 'sent') {
            $query->where('sender_id', $userId);
        } elseif ($this->filter === 'received') {
            $query->where('receiver_id', $userId);
        }

        $transactions = $query->latest()->paginate(10);

        return view('livewire.transaction-history', compact('transactions'));
    }
}
