# CMNHousing · Builder Portal (Laravel 12)

Frontend conversion of the Builder Dashboard into Laravel Blade + Vite.

## Stack
- Laravel 12
- Blade views (no Vue)
- Vite for CSS/JS
- Temporary sample data in `app/Support/DemoDashboardData.php` (no MySQL yet)

## Setup
```bash
composer install
cp .env.example .env   # if needed
php artisan key:generate
npm install
npm run build
php artisan serve
```

Open http://127.0.0.1:8000

For local Vite HMR while developing:
```bash
npm run dev
php artisan serve
```

## Notes
- Dashboard UI is rendered by Blade from controller data.
- `resources/js/app.js` only handles UI (sidebar, modals, toasts).
- MySQL / Eloquent will replace `DemoDashboardData` later.
