# Virtual Number Platform

A Laravel-based platform for renting virtual phone numbers, funding customer wallets, and receiving SMS messages in real time via provider webhooks.

## Features

- User authentication with email verification, password resets, and role-based access (Admin & Customer).
- Admin console for managing providers, syncing numbers from APIs, monitoring revenue, and issuing quick console commands.
- Customer dashboard to rent numbers, monitor SMS inboxes, and fund a wallet balance using integrated payment gateways (Stripe demo).
- REST API endpoints secured with Sanctum for retrieving numbers and messages, plus a webhook endpoint for inbound SMS (Twilio example provided).
- Scheduled command that releases expired numbers and notifies customers.
- TailwindCSS (CDN) UI with light/dark toggle and responsive layouts.

## Tech Stack

- Laravel 11, PHP 8.2+
- MySQL or PostgreSQL
- TailwindCSS CDN + Alpine.js
- Sanctum for API authentication
- Stripe/Paystack/Flutterwave ready (Stripe simulation included)
- Twilio integration sample and extensible provider manager

## Getting Started

1. **Install dependencies**
   ```bash
   composer install
   npm install # optional if you want to compile assets locally
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database & services**
   Update `.env` with your database credentials and provider API keys (Twilio SID/token, payment secrets, etc.).

4. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

   The seeder creates an admin (`admin@example.com` / `password`) and a customer (`customer@example.com` / `password`).

5. **Serve the application**
   ```bash
   php artisan serve
   ```

6. **Queue & scheduler (optional)**
   - For email notifications run a queue worker: `php artisan queue:work`
   - Schedule the expiration check: add `* * * * * php artisan schedule:run` to your cron.

## Provider Integration

Provider adapters implement `App\Services\Providers\ProviderClientInterface`. A Twilio client is included. Register additional drivers using `ProviderManager::extend()` (e.g., in a service provider).

## Webhooks & APIs

- **Webhook**: `POST /api/webhooks/sms/{provider}` expects Twilio-style payloads and stores incoming messages.
- **API** (Sanctum protected):
  - `GET /api/numbers`
  - `GET /api/numbers/{phoneNumber}/messages`

Obtain API tokens via `php artisan tinker` or a future developer portal.

## Testing

Run unit/feature tests with `php artisan test`.

## Deployment Notes

- Ensure HTTPS termination for webhook URLs.
- Configure Horizon/queues for production SMS processing.
- Set up mail transport for email notifications.

## License

MIT
