<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Wallet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $wallet->currency }}</p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                {{ number_format($wallet->balance, 2) }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Available Balance</p>
        </div>
    </div>
</div>
