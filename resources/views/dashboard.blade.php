<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('P2P Payments Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Real-time notification banner --}}
            <div
                x-data="{ show: false, message: '' }"
                x-on:livewire-notification.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 5000)"
                x-show="show"
                x-transition
                class="bg-green-500 text-white px-6 py-3 rounded-lg shadow"
            >
                <span x-text="message"></span>
            </div>

            {{-- Wallet Balance --}}
            <livewire:wallet-balance />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Send Money --}}
                <livewire:send-money />

                {{-- Top Up Wallet --}}
                <livewire:top-up-wallet />
            </div>

            {{-- Transaction History --}}
            <livewire:transaction-history />
        </div>
    </div>
</x-app-layout>
