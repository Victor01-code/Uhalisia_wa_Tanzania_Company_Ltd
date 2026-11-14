<p align="center">
  <img src="brand/logo.svg" width="160" alt="Uhalisia wa Tanzania Logo">
</p>

<h1 align="center">Uhalisia wa Tanzania — Safari & Tours Portal</h1>

<p align="center">
  <strong>Internal operations portal for managing leads, bookings, itineraries, payments, and client communications.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?logo=php" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap" alt="Bootstrap 5.3">
  <img src="https://img.shields.io/badge/MariaDB-10.4+-orange?logo=mariadb" alt="MariaDB">
  <img src="https://img.shields.io/badge/License-Proprietary-lightgrey" alt="License">
</p>

---

## Overview

A full-stack business operations portal for **Uhalisia wa Tanzania Company Ltd**, a safari and tours operator based in Arusha & Dar es Salaam. The system manages the complete lifecycle from initial lead capture through itinerary planning, payments, and post-trip reporting.

### Key Features

- **Lead Management** — capture inquiries from web forms, WordPress API, or manual entry
- **Booking Lifecycle** — full status tracking from inquiry to completion with audit trail
- **Itinerary Builder** — day-by-day trip planning with drag-and-drop reordering
- **PDF Generation** — branded quotes and itinerary vouchers via DomPDF
- **Client Portal** — passwordless magic-link access for clients to view their trip
- **Fleet Operations** — vehicle, driver, and guide management with overlap detection
- **Financial Tracking** — payments in USD/TZS with exchange rate conversion
- **Expense Management** — per-booking and general operational expenses
- **Reports & Exports** — financial summaries, profit analysis, and Excel/CSV exports
- **Email System** — automated confirmations, reminders, and weekly summaries
- **Document Vault** — secure file storage with client-visibility controls

---

## Technology Stack

| Component | Technology |
|---|---|
| **Backend** | PHP 8.2+, Laravel 12.x |
| **Database** | MariaDB 10.4+ / MySQL 8.0+ |
| **Frontend** | Blade Templates, Bootstrap 5.3.3, Bootstrap Icons 1.11 |
| **Fonts** | Merriweather (headings) + Inter (body), self-hosted WOFF2 |
| **JavaScript** | Alpine.js 3.x (interactivity), Chart.js 4.x (reports) |
| **PDF** | barryvdh/laravel-dompdf |
| **Excel/CSV** | phpoffice/phpspreadsheet |
| **Auth** | Session-based with bcrypt, role-based middleware |

> **No build step required.** All CSS/JS assets are locally bundled in `public/vendor/`. No Node.js, npm, or Webpack on production.

---

## Quick Start (Development)

### Prerequisites

- PHP 8.2+ with extensions: `mbstring`, `openssl`, `pdo_mysql`, `gd`, `zip`, `xml`
- MariaDB 10.4+ or MySQL 8.0+
- Composer 2.x

### Installation

```bash
# Clone or extract the project
cd uhalisia-portal

# Install PHP dependencies
composer install

# Configure environment
cp .env.example .env
# Edit .env with your database and mail settings

# Generate application key
php artisan key:generate

# Run migrations and seed demo data
php artisan migrate
php artisan db:seed

# Create admin user
php artisan app:create-admin

# Start development server
php artisan serve
```

Visit `http://localhost:8000/admin` to log in.

### Running Tests

```bash
php artisan test
```

---

## Project Structure

```
uhalisia-portal/
├── app/
│   ├── Console/Commands/       # CreateAdmin, DailyBackup, TripReminder, WeeklySummary
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 21 controllers (dashboard, leads, bookings, etc.)
│   │   │   ├── Api/            # InquiryController (WordPress integration)
│   │   │   ├── Client/         # ClientPortalController (magic-link access)
│   │   │   └── Public/         # BookingFormController (public /book form)
│   │   └── Middleware/         # RoleMiddleware
│   └── Models/                 # 19 Eloquent models
├── database/
│   ├── migrations/             # 13 migration files
│   ├── seeders/                # 7 seeders including demo data
│   └── factories/
├── docs/
│   ├── DECISIONS.md            # Architectural decisions log
│   ├── DEPLOY_CPANEL.md        # Production deployment guide
│   └── USER_GUIDE.md           # Staff user manual
├── public/
│   ├── vendor/                 # Bootstrap, Alpine.js, Chart.js, fonts
│   └── images/
├── resources/views/
│   ├── admin/                  # All admin panel views
│   ├── client/                 # Client portal views
│   ├── emails/                 # 7 email templates
│   ├── errors/                 # Branded 403, 404, 419, 500
│   ├── layouts/                # app, admin, client, public
│   ├── pdf/                    # Quote and itinerary PDF templates
│   └── public/                 # Booking form, thank you page
├── routes/
│   ├── web.php                 # 70+ web routes
│   ├── api.php                 # Inquiry API endpoint
│   └── console.php             # Scheduled tasks
├── storage/app/
│   ├── documents/              # Uploaded trip documents (private)
│   └── backups/                # Database backup files
└── tests/Feature/              # 12 feature test files
```

---

## User Roles

| Role | Access |
|---|---|
| **Admin** | Full system access |
| **Agent** | Leads, clients, bookings, tours, fleet management |
| **Accountant** | Bookings (view), payments, expenses, exchange rates, reports |

---

## Scheduled Tasks

Configure the Laravel scheduler cron entry on production:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

| Task | Schedule | Description |
|---|---|---|
| `app:daily-backup` | 02:00 daily | Database backup with 30-day rotation |
| `app:trip-reminder` | 08:00 daily | Email reminder for trips starting in 3 days |
| `app:weekly-summary` | Monday 07:00 | Weekly operational summary to admin |

---

## Documentation

- **[Deployment Guide](docs/DEPLOY_CPANEL.md)** — production setup on cPanel/shared hosting
- **[User Guide](docs/USER_GUIDE.md)** — staff manual for daily portal use
- **[Decisions Log](docs/DECISIONS.md)** — architectural decisions and rationale

---

## Environment Variables

Key `.env` settings (see `.env.example` for full list):

| Variable | Description |
|---|---|
| `APP_KEY` | Laravel encryption key (auto-generated) |
| `DB_*` | Database connection credentials |
| `MAIL_*` | SMTP email configuration |
| `COMPANY_*` | Company name, email, phone, website |
| `INQUIRY_API_TOKEN` | Secret token for WordPress API integration |

---

## License

Proprietary — Uhalisia wa Tanzania Company Ltd. All rights reserved.
