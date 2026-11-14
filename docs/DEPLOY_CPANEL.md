# Deployment Guide — cPanel / Shared Hosting

> **Audience:** System administrator deploying the Uhalisia wa Tanzania portal on a cPanel-based shared hosting environment.

---

## Prerequisites

| Requirement | Minimum |
|---|---|
| PHP | 8.2+ with extensions: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`, `zip` |
| MariaDB / MySQL | 10.4+ / 8.0+ |
| Disk Space | ~100 MB (application + vendor) |
| HTTPS | Required (Let's Encrypt or hosting-provided SSL) |

> [!IMPORTANT]
> No SSH access, Node.js, or Composer is required on the server. The release package includes all dependencies pre-built.

---

## 1. Database Setup

1. In cPanel → **MySQL® Databases**:
   - Create a new database: `cpanel_uhalisia_portal` (use your cPanel prefix)
   - Create a database user with a strong password
   - Add the user to the database with **ALL PRIVILEGES**

2. Note down:
   - Database name
   - Database username
   - Database password

---

## 2. Upload the Release Package

1. Download the latest `uhalisia-portal-vX.X.X.zip` release
2. In cPanel → **File Manager**, navigate to `public_html/` (or a subdomain directory)
3. Upload the ZIP file and extract it
4. The directory structure should look like:

```
public_html/portal/          ← or wherever you extract
├── app/
├── bootstrap/
├── config/
├── database/
├── public/                  ← this must be the web-accessible root
├── resources/
├── routes/
├── storage/
├── vendor/                  ← pre-built, no Composer needed
├── .env
├── artisan
└── ...
```

---

## 3. Configure the Document Root

### Option A: Subdomain (Recommended)
1. In cPanel → **Subdomains**, create `portal.yourdomain.com`
2. Set the document root to: `/home/cpaneluser/public_html/portal/public`

### Option B: Addon Domain
1. Set the document root to the `public/` directory inside the extracted folder

### Option C: Main Domain
1. Place `.htaccess` in the main `public_html/` to redirect to the `public/` subfolder:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ portal/public/$1 [L]
</IfModule>
```

> [!WARNING]
> The `public/` directory MUST be the web-accessible root. Never expose the project root directory to the web — this would leak `.env` credentials, database files, and application source code.

---

## 4. Configure Environment Variables

1. Copy `.env.example` to `.env` (if not already present)
2. Edit `.env` with your production values:

```env
APP_NAME="Uhalisia wa Tanzania"
APP_ENV=production
APP_KEY=                          # Generate below
APP_DEBUG=false                   # NEVER true in production
APP_TIMEZONE="Africa/Dar_es_Salaam"
APP_URL=https://portal.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cpanelprefix_uhalisia_portal
DB_USERNAME=cpanelprefix_dbuser
DB_PASSWORD=your_strong_password_here

SESSION_DRIVER=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=465
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@yourdomain.com"
MAIL_FROM_NAME="Uhalisia wa Tanzania Safaris"

COMPANY_NAME="Uhalisia wa Tanzania Company Ltd"
COMPANY_TAGLINE="Safaris and Tours"
COMPANY_EMAIL="info@uhalisiawatanzania.com"
COMPANY_PHONE="+255 784 590 722"
COMPANY_WEBSITE="www.uhalisiawatanzania.com"
COMPANY_OFFICES="Arusha & Dar es Salaam, Tanzania"

INQUIRY_API_TOKEN=generate_a_64_char_random_string
```

---

## 5. Generate Application Key

Use the cPanel **Terminal** (if available) or a PHP script:

### Via cPanel Terminal:
```bash
cd ~/public_html/portal
php artisan key:generate
```

### Via a temporary PHP file (if no Terminal):
Create `keygen.php` in the `public/` directory:

```php
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('key:generate', ['--force' => true]);
echo "APP_KEY generated successfully. DELETE THIS FILE NOW.";
```

Visit `https://portal.yourdomain.com/keygen.php`, then **immediately delete the file**.

---

## 6. Run Database Migrations

### Via cPanel Terminal:
```bash
cd ~/public_html/portal
php artisan migrate --force
php artisan db:seed --force
```

### Via temporary PHP file:
Create `migrate.php` in `public/`:

```php
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('migrate', ['--force' => true]);
echo "<pre>" . Artisan::output() . "</pre>";
$kernel->call('db:seed', ['--force' => true]);
echo "<pre>" . Artisan::output() . "</pre>";
echo "Done. DELETE THIS FILE NOW.";
```

> [!CAUTION]
> **Delete all temporary PHP files** (`keygen.php`, `migrate.php`) immediately after use. Leaving them accessible is a critical security vulnerability.

---

## 7. Set Directory Permissions

The following directories must be writable by the web server (typically `chmod 775`):

```
storage/                     # All subdirectories
storage/app/documents/       # Uploaded trip documents
storage/app/backups/         # Database backup files
storage/framework/cache/
storage/framework/sessions/
storage/framework/views/
storage/logs/
bootstrap/cache/
```

In cPanel File Manager, right-click each directory → **Change Permissions** → set to `0775`.

---

## 8. Create the Admin User

### Via Terminal:
```bash
php artisan app:create-admin
```

### Via temporary PHP file:
```php
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('app:create-admin');
echo "<pre>" . Artisan::output() . "</pre>";
echo "DELETE THIS FILE NOW.";
```

---

## 9. Configure Cron Jobs

In cPanel → **Cron Jobs**, add the following entry to run the Laravel scheduler every minute:

```
* * * * * cd /home/cpaneluser/public_html/portal && php artisan schedule:run >> /dev/null 2>&1
```

This single cron entry handles all scheduled tasks:
- **02:00 daily:** Database backup with 30-day rotation
- **08:00 daily:** Trip reminder emails (3 days before departure)
- **07:00 Monday:** Weekly operational summary email to admin

---

## 10. Configure SMTP Email

1. In cPanel → **Email Accounts**, create (or verify) the sending email address
2. Note the SMTP settings (host, port, username, password)
3. Update `.env` with correct SMTP credentials
4. Test email delivery:

```bash
php artisan tinker
>>> Mail::raw('Test email from Uhalisia Portal', function ($m) { $m->to('your@email.com')->subject('Test'); });
```

---

## 11. WordPress Integration (Inquiry API)

To connect your WordPress website's booking/quote form to the portal:

### API Endpoint
```
POST https://portal.yourdomain.com/api/inquiries
```

### Authentication
Include the API token as either:
- **Header:** `Authorization: Bearer YOUR_INQUIRY_API_TOKEN`
- **Query param:** `?token=YOUR_INQUIRY_API_TOKEN`

### Expected Fields
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "country": "United States",
    "tour": "10-day-luxury-northern-circuit",
    "message": "Interested in a family safari",
    "adults": 2,
    "children": 1,
    "preferred_date": "2026-12-15",
    "utm_source": "google",
    "utm_medium": "cpc",
    "website_url": ""
}
```

> The `website_url` field is a honeypot — it must be empty or the request will be silently rejected.

### WordPress Contact Form 7 / Elementor
The API accepts both flat and nested field formats. Set the form's custom webhook to the endpoint URL above with the token as a query parameter.

---

## 12. SSL / HTTPS

1. In cPanel → **SSL/TLS** or **Let's Encrypt**, install/renew the SSL certificate
2. In cPanel → **Domains** → enable **Force HTTPS Redirect**
3. Verify `APP_URL` in `.env` starts with `https://`

---

## 13. Post-Deployment Checklist

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_KEY` is generated (not empty)
- [ ] Database migrations have been run
- [ ] Admin user has been created and can log in
- [ ] `public/` is the document root (not the project root)
- [ ] `storage/` and `bootstrap/cache/` are writable
- [ ] Cron job is active (`* * * * *`)
- [ ] SMTP email is configured and tested
- [ ] SSL certificate is active and HTTPS is forced
- [ ] All temporary PHP files have been deleted
- [ ] `INQUIRY_API_TOKEN` is set to a unique random string
- [ ] Test the `/book` form submits correctly
- [ ] Test the client portal magic-link flow
- [ ] Verify PDF quote and itinerary generate correctly

---

## Troubleshooting

### 500 Internal Server Error
1. Check `storage/logs/laravel.log` for the detailed error
2. Verify `.env` file exists and has correct values
3. Confirm `storage/` permissions are `775`

### Blank White Page
1. Enable `APP_DEBUG=true` temporarily to see the error
2. Most common cause: missing PHP extensions (`gd`, `zip`, `mbstring`)

### Emails Not Sending
1. Verify SMTP credentials in `.env`
2. Check if the hosting provider blocks outgoing SMTP on port 465/587
3. Try `MAIL_MAILER=sendmail` as an alternative

### PDF Generation Fails
1. Ensure the `gd` PHP extension is enabled
2. Check that `storage/fonts/` is writable (DomPDF font cache)

### Session / Login Issues
1. Verify `SESSION_DRIVER=database` and the sessions table exists
2. Clear cached config: create a temp PHP file to run `Artisan::call('config:clear')`

---

## Updating the Application

1. Back up the current installation and database
2. Upload the new release ZIP
3. Extract, overwriting existing files (preserve `.env`)
4. Run migrations via Terminal or temp PHP file:
   ```bash
   php artisan migrate --force
   ```
5. Clear caches:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```
