<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::factory()->create([
            'name' => 'Alice Demo',
            'email' => 'alice@example.com',
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob Demo',
            'email' => 'bob@example.com',
        ]);

        // Create wallets with initial balances
        $alice->getOrCreateWallet()->deposit(500.00);
        $bob->getOrCreateWallet()->deposit(250.00);
    }
}
