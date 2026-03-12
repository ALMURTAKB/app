<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Up Wallet</h3>

    @if ($success)
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            ✅ Wallet topped up successfully!
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            ❌ {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit="topUp" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Amount (USD)
            </label>
            <input
                type="number"
                wire:model="amount"
                step="1"
                min="1"
                placeholder="100"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
            @error('amount')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            @foreach ([10, 25, 50, 100] as $preset)
                <button
                    type="button"
                    wire:click="$set('amount', {{ $preset }})"
                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    +${{ $preset }}
                </button>
            @endforeach
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
        >
            <span wire:loading.remove>💰 Top Up Wallet</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>
</div>
