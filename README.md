## Laravel Assessment – Hipster Shop

An example e-commerce application built with:

- Laravel 12, PHP 8.2
- Livewire 3 (SPA-like pages without a front-end framework)
- Laravel Reverb + Echo for realtime notifications/broadcasting
- Queue-powered CSV product import (batched jobs)

This project includes a customer area (browse products, cart/checkout, orders, profile) and an admin area (manage products, import CSV, manage orders, view notifications).

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- SQLite (default) or another DB supported by Laravel

---

## Quick Start

1) Install dependencies

```bash
composer install
npm install
```

2) Environment and key

```bash
cp .env.example .env
php artisan key:generate
```

By default the app uses SQLite. Ensure the file exists:

```bash
mkdir -p database
type NUL > database\database.sqlite  # Windows
# or: touch database/database.sqlite
```

3) Migrate and seed

```bash
php artisan migrate --seed
```

4) Storage symlink for product images

```bash
php artisan storage:link
```

5) Start the app (single command)

```bash
composer run dev
```

This runs all of the following concurrently:

- php artisan serve (HTTP server)
- php artisan queue:work (queues for imports/notifications)
- php artisan reverb:start (realtime WebSocket server)
- npm run dev (Vite build with HMR)

Alternatively, run each in its own terminal.

---

## Seeded demo accounts

- Customer: customer@example.com / password
- Admin: admin@example.com / password

See `database/seeders/DatabaseSeeder.php` for details.

---

## Features overview

- Products: browse, search, pagination (`App\Livewire\Customer\Products`)
- Cart/Checkout: creates orders, decrements stock, notifies admins
- Orders: customers see their orders; status updates are realtime
- Admin: product CRUD, CSV import, orders management, notifications
- Realtime: private channels `App.Models.User.{id}` and `App.Models.Admin.{id}`

Notifications and Livewire UI updates are wired via `resources/js/toasts.js` and `resources/js/bootstrap.js`. When an order status changes, a payload is broadcast and Livewire components selectively refresh.

---

## CSV import

Admin > Import accepts a CSV with header columns:

```
name,description,price,image,category,stock
```

Large files are chunked and processed with queued jobs (`App\Jobs\ImportProductsChunk`). Make sure the queue worker is running.

---

## Realtime & broadcasting

- Broadcasting is enabled via `App\Providers\BroadcastServiceProvider` and `routes/channels.php`.
- Reverb runs with `php artisan reverb:start`. The frontend subscribes to private user/admin channels and dispatches Livewire events on notifications.
- Ensure `.env` contains a correct `APP_URL` and that you access the app using the same host to keep sessions valid (needed for `/broadcasting/auth`).

---

## Useful scripts

- Start everything: `composer run dev`
- Tests: `composer test`
- Lint/format (optional): `./vendor/bin/pint` or `./vendor/bin/duster fix`

---

## Troubleshooting

- 403 on `/broadcasting/auth`:
  - Make sure you are logged in and using the same host as `APP_URL`.
  - Session domain and CSRF must match; avoid mixing `localhost` and `127.0.0.1`.
- Realtime not updating:
  - Ensure `php artisan reverb:start` is running.
  - Ensure `npm run dev` is running so Echo is loaded (`resources/js/bootstrap.js`).
  - Check console for channel subscription logs from `resources/js/toasts.js`.
- Imports not progressing:
  - Ensure `php artisan queue:work` is running.
  - Check `jobs` and `failed_jobs` tables.
- Product images not visible:
  - Run `php artisan storage:link`.

---

## License

MIT
