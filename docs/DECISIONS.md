# Decisions Log

This document records every assumption and design decision made during development.

## Architecture

| # | Decision | Rationale | Date |
|---|---|---|---|
| 1 | **Laravel 12.x on PHP 8.2.12** | Latest stable release cleanly resolving dependencies on PHP 8.2 without unmaintained subpackages. | 2026-10-26 |
| 2 | **Session timeout: 2 hours** | Standard for business applications. Long enough for admin work sessions, short enough for security. | 2026-10-26 |
| 3 | **Booking reference format: UWT-YYYY-NNNN** | Zero-padded 4-digit sequence, resetting each calendar year. Allows up to 9999 bookings per year. | 2026-10-26 |
| 4 | **Default deposit: 30%** | Common in East African tour industry. Stored in `settings` table, admin-editable. | 2026-10-26 |
| 5 | **Quote validity: 14 days** | Industry standard. Stored in `settings` table, admin-editable. | 2026-10-26 |
| 6 | **Assignment overlap: hard block** | Prevents double-booking of vehicles, drivers, and guides. Saving is blocked, not just warned. | 2026-10-26 |
| 7 | **Email templates: Blade + settings** | Core layout in Blade files; customisable sections (signature, payment terms) stored in `settings` and injected. | 2026-10-26 |
| 8 | **Polymorphic assignments via enum** | `assignments.resource_type` is an enum (vehicle/driver/guide) + `resource_id` integer. Simpler than Eloquent morphs for three fixed types. | 2026-10-26 |
| 9 | **Client token approach** | `Str::random(64)` stored on booking. Links are `/client/b/{token}`. Expiry checked via `created_at` + 7 days. Regenerated on each new request. | 2026-10-26 |
| 10 | **Exchange rate stored per payment** | Each payment stores `exchange_rate_used` so historical conversions are immutable even if the rate is later corrected. | 2026-10-26 |
| 11 | **Chart.js 4.x for reports** | Lightweight, no build step, locally bundled. Supports bar, line, pie, doughnut charts needed for reports. | 2026-10-26 |
| 12 | **Alpine.js 3.x for interactivity** | Minimal JS framework for drag-reorder, dropdowns, toggle elements. No build step required. | 2026-10-26 |
| 13 | **Fonts: Merriweather (headings) + Inter (body)** | Premium serif + clean sans-serif combination. Self-hosted WOFF2 files, no CDN dependency. | 2026-10-26 |
| 14 | **Passport encryption** | Using Laravel's `Crypt::encryptString()` / `Crypt::decryptString()` for passport numbers at rest. | 2026-10-26 |
| 15 | **Documents stored in storage/app/documents/** | Outside public web root. Served through authenticated controller route with streaming. | 2026-10-26 |
| 16 | **Honeypot field name: website_url** | Both `/book` form and `/api/inquiries` use this field. Must be empty or request is rejected silently. | 2026-10-26 |
| 17 | **Enabled PHP zip and gd extensions in php.ini** | Required by Composer, DomPDF, and PhpSpreadsheet. Enabled in `C:\xampp\php\php.ini`. | 2026-10-26 |
| 18 | **Dual API Authentication for /api/inquiries** | Supports both `Authorization: Bearer <token>` and `?token=<token>` query parameter to maximize WordPress form compatibility. | 2026-10-26 |
| 19 | **Inlined Blueprint Timestamps for MariaDB Windows** | Replaced separate `ALTER TABLE` created_at index with inlined Blueprint index to bypass Windows MariaDB metadata lock stalls. | 2026-10-26 |
| 20 | **Base64 Embedded Logo for DomPDF** | Encodes brand logo as inline base64 data URI in PDF views to ensure deterministic rendering across all server environments without URI access issues. | 2026-10-27 |
| 21 | **Auto-Resequence on Day Deletion** | Deleting any blueprint day or booking day automatically renumbers remaining days (1..N) and recalculates calendar dates from booking start date. | 2026-10-27 |
| 22 | **Deterministic PDF Download Filenames** | Replaced default library download handlers with explicit `Content-Disposition: attachment; filename="UWT-Quote-...pdf"` HTTP headers combined with HTML5 `download` attributes to prevent browsers from assigning temporary UUIDs. | 2026-10-27 |
| 23 | **Hard-Block Resource Overlap Detection** | Enforces calendar date collision checks (`start_date <= endDate AND end_date >= startDate`) across vehicles, drivers, and guides with detailed conflict reporting (conflicting booking reference and dates). | 2026-10-27 |
| 24 | **Secure Private Document Vault & Visibility** | Stored in private `storage/app/documents/{booking_id}/` (non-public). Streamed only via authenticated staff routes and token-verified client routes when `visible_to_client = true`. | 2026-10-27 |
| 25 | **Monthly Grid Resource Calendar** | Visual timeline view of vehicle, driver, and guide assignments presented as a month-grid table with color-coded cells, click-to-navigate to booking, and tooltip details. No external calendar library; pure HTML/CSS table with Bootstrap. | 2026-10-28 |
| 26 | **Cross-Device Responsive Design (Mobile, Tablet, Desktop)** | Standardized responsive layouts across smartphones, tablets, and desktops. Includes an offcanvas sidebar drawer on < 992px with hamburger toggle, sticky header, touch-friendly horizontal momentum scrolling for tables and tab bars, minimum tap target sizing, and responsive typography. | 2026-10-28 |

