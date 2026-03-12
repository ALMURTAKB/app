# Laravel Livewire P2P Payments App

A peer-to-peer payments application built with **Laravel 11**, **Livewire 3**, **Jetstream**, **Tailwind CSS**, and **Alpine.js**.

## ✨ Features

- ✅ **User Registration & Login** — powered by Laravel Jetstream (with profile management, 2FA support)
- ✅ **Wallet System** — every user gets a digital wallet with a USD balance
- ✅ **Send Money** — transfer funds to another user by email address
- ✅ **Top Up Wallet** — add funds to your wallet (simulated)
- ✅ **Transaction History** — view sent/received transactions with filters and pagination
- ✅ **Real-time Notifications** — receive instant notifications when money arrives (via Laravel Echo + Pusher)
- ✅ **Dark Mode** — full dark mode support via Tailwind CSS

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 |
| Frontend | Livewire 3.x + Alpine.js + Tailwind CSS |
| Auth | Laravel Jetstream |
| Real-time | Laravel Echo + Pusher |
| Database | SQLite (dev) / MySQL / PostgreSQL |
| Queue | Redis + Laravel Queues |

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- A [Pusher](https://pusher.com) account (or [Soketi](https://soketi.app) for self-hosted)

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Configure your database in .env (defaults to SQLite)
# DB_CONNECTION=sqlite

# 5. Run migrations
php artisan migrate

# 6. (Optional) Seed demo users
php artisan db:seed

# 7. Install frontend dependencies
npm install

# 8. Build assets
npm run build
# or for development:
npm run dev

# 9. Start the server
php artisan serve
```

### Pusher Configuration

Update your `.env` file with your Pusher credentials:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

## 📁 Project Structure

```
app/
├── Events/
│   └── MoneyReceived.php        # Broadcast event for real-time notifications
├── Livewire/
│   ├── WalletBalance.php        # Displays current wallet balance
│   ├── SendMoney.php            # Send money to another user
│   ├── TransactionHistory.php   # Paginated transaction list with filters
│   └── TopUpWallet.php          # Add funds to wallet
├── Models/
│   ├── User.php                 # Extended with wallet & transaction relations
│   ├── Wallet.php               # Wallet model with deposit/withdraw methods
│   └── Transaction.php         # Transaction record model
└── Services/
    └── WalletService.php        # Core business logic for payments

database/migrations/
├── ...create_wallets_table.php
└── ...create_transactions_table.php

resources/views/
├── dashboard.blade.php          # Main P2P dashboard
└── livewire/
    ├── wallet-balance.blade.php
    ├── send-money.blade.php
    ├── transaction-history.blade.php
    └── top-up-wallet.blade.php

routes/
├── channels.php                 # Private channel authorization
└── web.php
```

## 🧪 Testing

```bash
php artisan test
```

## 📝 Demo Accounts

After running `php artisan db:seed`:

| Name | Email | Password | Balance |
|------|-------|----------|---------|
| Alice Demo | alice@example.com | password | $500.00 |
| Bob Demo | bob@example.com | password | $250.00 |

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
