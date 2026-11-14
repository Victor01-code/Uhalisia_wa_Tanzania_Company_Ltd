# User Guide — Uhalisia wa Tanzania Portal

> **Audience:** Office staff (admin, agent, accountant) who use the portal daily.

---

## Table of Contents

1. [Logging In](#1-logging-in)
2. [Dashboard Overview](#2-dashboard-overview)
3. [Leads & Inquiries](#3-leads--inquiries)
4. [Clients](#4-clients)
5. [Bookings & Trips](#5-bookings--trips)
6. [Itinerary Builder](#6-itinerary-builder)
7. [Travelers](#7-travelers)
8. [PDF Quotes & Itineraries](#8-pdf-quotes--itineraries)
9. [Client Portal](#9-client-portal)
10. [Tour Templates](#10-tour-templates)
11. [Vehicles, Drivers & Guides](#11-vehicles-drivers--guides)
12. [Resource Assignments](#12-resource-assignments)
13. [Payments](#13-payments)
14. [Expenses](#14-expenses)
15. [Exchange Rates](#15-exchange-rates)
16. [Reports & Exports](#16-reports--exports)
17. [Documents](#17-documents)
18. [Settings](#18-settings)
19. [Staff Accounts](#19-staff-accounts)
20. [Public Booking Form](#20-public-booking-form)
21. [API Integration](#21-api-integration-wordpress)

---

## 1. Logging In

1. Navigate to `https://portal.yourdomain.com/admin`
2. Enter your email and password
3. Click **Sign In**

**Security features:**
- Login is throttled to **5 attempts per minute** — wait 60 seconds after 5 failed attempts
- Sessions expire after **2 hours** of inactivity
- Only active accounts can log in (disabled accounts are blocked)

**Roles:**
| Role | Access |
|---|---|
| **Admin** | Full access to everything |
| **Agent** | Leads, clients, bookings, tours, vehicles, drivers, guides |
| **Accountant** | Bookings (view), payments, expenses, exchange rates, reports |

---

## 2. Dashboard Overview

The dashboard shows at-a-glance metrics:

- **New Leads** — unprocessed inquiries awaiting action
- **Upcoming Trips** — confirmed/paid trips departing in the next 30 days
- **Trips in Progress** — safaris currently underway
- **Unpaid Balances** — total outstanding balance across all active bookings

Below the stats you'll find:
- **Recent Leads** — latest 6 inquiries with status badges
- **Upcoming Departures** — next trips with client name, dates, and status
- **Recent Payments** — latest 5 recorded payments

---

## 3. Leads & Inquiries

**Sidebar → Leads & Inquiries**

Leads arrive from three sources:
1. **Public `/book` form** — visitors fill out the booking form on your website
2. **API (`/api/inquiries`)** — WordPress or other website forms submit automatically
3. **Manual entry** — staff creates leads manually

### Lead Statuses

| Status | Meaning |
|---|---|
| `New` | Just received, needs attention |
| `Contacted` | Staff has reached out to the client |
| `Quoted` | A formal quote has been sent |
| `Won` | Converted into a confirmed booking |
| `Lost` | Client decided not to proceed |

### Working with Leads

1. **View lead details** — click any lead to see full information, including raw form data
2. **Update status** — use the status buttons on the lead detail page
3. **Convert to Booking** — click **"Convert to Booking"** to:
   - Create a new client (or match existing by email)
   - Create a new booking pre-filled with the lead's information
   - Link the lead to the booking

---

## 4. Clients

**Sidebar → Clients**

Clients are the people who book trips. Each client has:
- Full name, email, phone, country
- Notes (internal)
- Linked bookings

### Creating Clients

Clients are usually created automatically when:
- A lead is converted to a booking
- A public booking form is submitted

You can also create clients manually via the **"New Client"** button.

---

## 5. Bookings & Trips

**Sidebar → Bookings & Trips**

### Booking Reference

Every booking gets an automatic reference in the format: **UWT-2026-0001**
- `UWT` = company prefix
- `2026` = year of the trip start date
- `0001` = sequential number (resets each year)

### Creating a Booking

1. Click **"Create New Booking"**
2. Select the client (or create a new one)
3. Optionally select a Tour Template to pre-fill the itinerary
4. Fill in trip details: title, dates, adults, children, price, payment mode
5. Click **"Create Booking"**

### Payment Mode

| Mode | Meaning |
|---|---|
| **Company Handles** | UWT collects payment and manages finances. Appears in financial reports. |
| **Client Direct** | Client pays hotels/parks directly. Booking tracked but not in income reports. |

### Booking Statuses

Statuses follow this progression:

```
Inquiry → Quote Sent → Confirmed → Paid in Full → In Progress → Completed
                                                                    ↗
                              Cancelled ←─────────── (from any status)
```

To change status, use the status transition buttons on the booking detail page. Status changes are logged with timestamps and the staff member who made them.

---

## 6. Itinerary Builder

On a booking detail page, the **Itinerary Days** section allows you to build a day-by-day plan.

### Building from a Tour Template

1. When creating a booking, select a **Tour Template**
2. The template's days are automatically copied into the booking
3. Dates are calculated from the booking's start date

### Manual Editing

- **Add Day** — click the "Add Day" button, fill in title, description, destination, accommodation, meals, activities
- **Edit Day** — click the edit button on any day
- **Delete Day** — removes the day and automatically renumbers remaining days
- **Reorder Days** — drag and drop days to rearrange them
- **Sync Dates** — recalculates calendar dates from the booking start date

---

## 7. Travelers

On the booking detail page, the **Travelers** section tracks every person on the trip.

For each traveler:
- Full name
- Nationality
- Passport number (encrypted at rest — only visible to authorised staff)
- Date of birth
- Dietary notes
- Emergency contact

> **Privacy:** Passport data is encrypted in the database. Access to passport information is logged in the audit trail.

---

## 8. PDF Quotes & Itineraries

### Generating PDFs

On the booking detail page, use the dropdown buttons:

- **Quote PDF** — branded quote with trip summary, pricing, inclusions/exclusions, payment terms
- **Itinerary Voucher PDF** — day-by-day itinerary with accommodations, meals, and activities

Each dropdown offers:
1. **Preview in Browser** — opens the PDF in a new tab
2. **Download PDF** — saves with a descriptive filename (e.g., `UWT-Quote-UWT-2026-0001.pdf`)
3. **Email to Client** — sends the PDF as an attachment via email

### Emailing PDFs

When you click "Email Quote to Client" or "Email Voucher to Client":
1. A confirmation modal appears
2. The email is sent to the client's email address
3. The booking status is automatically suggested to advance (e.g., to "Quote Sent")

---

## 9. Client Portal

Clients access their booking information through a secure, passwordless portal.

### How It Works

1. Client visits `https://portal.yourdomain.com/client`
2. They enter their email address
3. If a matching booking exists, a **magic link** is emailed (valid for 7 days)
4. The link opens a read-only view of their booking

### What Clients See

- Trip summary (title, dates, status)
- Day-by-day itinerary
- Assigned driver/guide (name + phone number)
- Payment summary (total, paid, balance in USD)
- Downloadable documents marked as "visible to client"
- "Download Itinerary PDF" button
- Contact information (email + WhatsApp)

### Staff Preview

On any booking detail page, click **"Client Portal"** to preview exactly what the client sees.

---

## 10. Tour Templates

**Sidebar → Tour Templates** (Admin only)

Tour templates are reusable blueprints that speed up booking creation.

### Creating a Template

1. Click **"Create New Tour"**
2. Fill in: name, slug, type (safari/climb/beach/cultural/combo), duration, base price, active status
3. Add day-by-day blueprint: title, description, destination, accommodation, meals, activities

### Using Templates

When creating a booking, select a tour template. All its blueprint days are copied as the booking's itinerary, with dates calculated from the start date. You can then customise the copied days freely without affecting the template.

---

## 11. Vehicles, Drivers & Guides

**Sidebar → Vehicles / Drivers / Safari & Trek Guides** (Admin, Agent)

### Vehicles
- Name (e.g., "Toyota Land Cruiser #3")
- Plate number
- Type, seats, active status, notes

### Drivers
- Name, phone, languages spoken, licence number, active status

### Guides
- Name, phone, languages spoken, permit number, active status

Mark resources as **inactive** when they're out of service or unavailable.

---

## 12. Resource Assignments

On the booking detail page, the **Assignments** section lets you assign vehicles, drivers, and guides to a trip.

### Creating an Assignment

1. Select the resource type (vehicle, driver, or guide)
2. Select the specific resource
3. Set the assignment date range

### Overlap Detection

The system **blocks** double-booking. If a vehicle, driver, or guide is already assigned to another trip during the same dates, the assignment is rejected with a detailed error message showing which booking has the conflict.

---

## 13. Payments

**Sidebar → Payments** (Admin, Accountant)

### Recording a Payment

1. Click **"Record Payment"** (from sidebar or from a booking page)
2. Select the booking
3. Fill in: date, amount, currency (USD or TZS), exchange rate, method, reference, type
4. Submit

### Currency Conversion

- Enter the amount in either **USD** or **TZS**
- The system auto-fills the current month's exchange rate
- Both USD and TZS equivalents are calculated and stored
- The exchange rate used is **locked** on each payment — changing the rate later won't affect past records

### Payment Types

| Type | Usage |
|---|---|
| **Deposit** | Initial partial payment |
| **Balance** | Remaining amount |
| **Refund** | Money returned to client (subtracted from paid total) |
| **Other** | Miscellaneous |

### Auto-Status Suggestion

After recording a payment, the system may suggest updating the booking status:
- **Deposit received** → suggests advancing to "Confirmed"
- **Balance = $0** → suggests advancing to "Paid in Full"

You must confirm the status change — it's never forced automatically.

---

## 14. Expenses

**Sidebar → Expenses** (Admin, Accountant)

Track all operational costs:

### Expense Categories
- Fuel
- Driver allowance
- Park fees
- Accommodation
- Vehicle service
- Vehicle checkup
- Insurance
- Licences
- Tax
- Other

### Per-Booking vs. General

- **Linked to a booking** — select a booking to track trip-specific costs
- **General (no booking)** — overhead costs like insurance, licences, office expenses

All expenses are stored in TZS with the current exchange rate for USD reporting.

---

## 15. Exchange Rates

**Sidebar → Exchange Rates (TZS/USD)** (Admin, Accountant)

Manage the monthly TZS-per-USD exchange rate:

1. Select the calendar year
2. Enter or update the rate for each month
3. Click **Save**

Rates are used for:
- Converting payments between USD and TZS
- Converting expenses for USD reporting
- Financial report calculations

If a month has no rate set, the system falls back to the most recent previous month's rate. Default fallback: 2,600 TZS/USD.

---

## 16. Reports & Exports

**Sidebar → Reports & Excel Export** (Admin, Accountant)

### Dashboard Reports

The reports page shows:
- **12-Month Financial Ledger** — income, expenses, and net profit per month (bar chart + table)
- **Bookings by Tour** — pie chart of trip distribution
- **Leads by Source** — pie chart of inquiry origins
- **Lead Conversion Rate** — percentage of leads converted to bookings
- **Per-Booking Profit** — table showing revenue, expenses, and profit for each booking

### Excel/CSV Exports

Three export options:
1. **Monthly Financial Summary** — one row per month with income/expense/net in both USD and TZS
2. **Per-Booking Profit** — detailed profit analysis for every booking
3. **Payments Ledger** — every payment record with currency conversion details

### Audit Logs

**Sidebar → Audit Logs** (Admin only)

Track who viewed sensitive data, created payments, and made changes. Each log entry shows:
- Action performed
- Entity type and ID
- Description
- Staff member
- Timestamp

---

## 17. Documents

On the booking detail page, the **Documents** section allows file management.

### Uploading

- Accepted formats: PDF, JPG, JPEG, PNG
- Maximum size: 5 MB per file
- Files are stored securely **outside** the public web directory

### Visibility

Each document has a **"Visible to Client"** toggle:
- **On** — client can download it from the client portal
- **Off** — only staff can access it

### Security

Documents are served through authenticated routes. Direct URL access is blocked. A client can only download documents marked as visible on their specific booking.

---

## 18. Settings

**Sidebar → Portal Settings** (Admin only)

Configure:
- **Company Details** — name, tagline, email, phone, website, office locations
- **Quote Defaults** — default deposit percentage (30%), quote validity (14 days)
- **Booking Terms** — standard inclusions/exclusions text for PDF quotes
- **Email Signature** — appended to outgoing email templates

---

## 19. Staff Accounts

**Sidebar → Staff Accounts** (Admin only)

### Creating a Staff Account

1. Click **"New Staff Account"**
2. Fill in name, email, password, role
3. Enable/disable the account

### Roles

| Role | Permissions |
|---|---|
| **Admin** | Everything: leads, clients, bookings, tours, fleet, payments, expenses, reports, settings, users |
| **Agent** | Leads, clients, bookings, tours, vehicles, drivers, guides |
| **Accountant** | Bookings (view), payments, expenses, exchange rates, reports |

### Deactivating Accounts

Set an account to **Inactive** to block login without deleting the account. This preserves the audit trail (who made changes, who received payments, etc.).

---

## 20. Public Booking Form

Your public website at `/book` provides a booking form for visitors.

### Pre-filling

Link to the form with query parameters to pre-fill fields:
- `?tour=10-day-luxury-northern-circuit` — pre-selects the tour dropdown
- `?interest=Family Safari` — pre-fills the safari interest field

Example: `https://portal.yourdomain.com/book?tour=kilimanjaro-machame-route&interest=Summit%20attempt`

### Spam Protection

- **Honeypot field** — a hidden `website_url` field that must be empty
- **Rate limiting** — maximum 5 submissions per minute per IP address
- **Server-side validation** — all fields are validated server-side

### Flow

1. Visitor fills the form and submits
2. They see a "Thank You" confirmation page
3. A **confirmation email** is sent to the visitor
4. An **office notification email** is sent to your team
5. A new **Lead** appears in the portal with status "New"

---

## 21. API Integration (WordPress)

Your WordPress website can submit inquiries directly to the portal via the API.

### Endpoint
```
POST https://portal.yourdomain.com/api/inquiries
```

### Authentication
Include the token from `.env` (`INQUIRY_API_TOKEN`) as:
- Header: `Authorization: Bearer YOUR_TOKEN`
- Or query parameter: `?token=YOUR_TOKEN`

### Response

**Success (201):**
```json
{
    "success": true,
    "message": "Inquiry received successfully.",
    "lead_id": 42
}
```

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": { "email": ["The email field is required."] }
}
```

**Authentication Error (401):**
```json
{
    "success": false,
    "message": "Invalid API token."
}
```
