<?php

use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingDayController;
use App\Http\Controllers\Admin\BookingPdfController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ExchangeRateController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\GuideController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ResourceCalendarController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourDayController;
use App\Http\Controllers\Admin\TravelerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Client\ClientPortalController;
use App\Http\Controllers\Public\BookingFormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('book.show');
});

Route::get('/book', [BookingFormController::class, 'show'])->name('book.show');
Route::post('/book', [BookingFormController::class, 'submit'])->name('book.submit');
Route::get('/book/thank-you', [BookingFormController::class, 'thankYou'])->name('book.thankyou');

/*
|--------------------------------------------------------------------------
| Client Portal Routes (Passwordless Magic Link, Public PDFs & Documents)
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/', [ClientPortalController::class, 'showLoginForm'])->name('login');
    Route::post('/request-link', [ClientPortalController::class, 'requestLink'])->name('request_link');
    Route::post('/link-request', [ClientPortalController::class, 'requestLink'])->name('link.request');
    Route::get('/b/{token}', [ClientPortalController::class, 'showBooking'])->name('show');
    Route::get('/b/{token}/quote-pdf/{filename?}', [BookingPdfController::class, 'clientQuotePdf'])->name('quote_pdf');
    Route::get('/b/{token}/itinerary-pdf/{filename?}', [BookingPdfController::class, 'clientItineraryPdf'])->name('itinerary_pdf');
    Route::get('/b/{token}/documents/{document}/download', [ClientPortalController::class, 'downloadDocument'])->name('documents.download');
});

/*
|--------------------------------------------------------------------------
| Staff / Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Staff / Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // 1. Dashboard (All staff roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Leads & Inquiries (Admin, Agent)
    Route::middleware(['role:admin,agent'])->group(function () {
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.update_status');
        Route::post('/leads/{lead}/convert', [LeadController::class, 'convertToBooking'])->name('leads.convert');

        Route::resource('clients', ClientController::class);
    });

    // 3. Bookings & Trips (All staff roles with permission controls)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update_status');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // 3a. Live Itinerary Days Editor
    Route::post('/bookings/{booking}/days', [BookingDayController::class, 'store'])->name('bookings.days.store');
    Route::put('/bookings/{booking}/days/{day}', [BookingDayController::class, 'update'])->name('bookings.days.update');
    Route::delete('/bookings/{booking}/days/{day}', [BookingDayController::class, 'destroy'])->name('bookings.days.destroy');
    Route::post('/bookings/{booking}/days/reorder', [BookingDayController::class, 'reorder'])->name('bookings.days.reorder');
    Route::post('/bookings/{booking}/days/redate', [BookingDayController::class, 'autoRedate'])->name('bookings.days.redate');

    // 3b. Travelers Management
    Route::post('/bookings/{booking}/travelers', [TravelerController::class, 'store'])->name('bookings.travelers.store');
    Route::put('/bookings/{booking}/travelers/{traveler}', [TravelerController::class, 'update'])->name('bookings.travelers.update');
    Route::delete('/bookings/{booking}/travelers/{traveler}', [TravelerController::class, 'destroy'])->name('bookings.travelers.destroy');

    // 3c. PDF Generation & Dispatching
    Route::get('/bookings/{booking}/quote-pdf/{filename?}', [BookingPdfController::class, 'quotePdf'])->name('bookings.quote_pdf');
    Route::get('/bookings/{booking}/itinerary-pdf/{filename?}', [BookingPdfController::class, 'itineraryPdf'])->name('bookings.itinerary_pdf');
    Route::post('/bookings/{booking}/send-quote', [BookingPdfController::class, 'sendQuoteEmail'])->name('bookings.send_quote');
    Route::post('/bookings/{booking}/send-itinerary', [BookingPdfController::class, 'sendItineraryEmail'])->name('bookings.send_itinerary');

    // 3d. Resource Assignments & Overlap Detection
    Route::post('/bookings/{booking}/assignments', [AssignmentController::class, 'store'])->name('bookings.assignments.store');
    Route::delete('/bookings/{booking}/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('bookings.assignments.destroy');

    // 3e. Secure Trip Documents
    Route::post('/bookings/{booking}/documents', [DocumentController::class, 'store'])->name('bookings.documents.store');
    Route::get('/bookings/{booking}/documents/{document}/download', [DocumentController::class, 'download'])->name('bookings.documents.download');
    Route::patch('/bookings/{booking}/documents/{document}/toggle-visibility', [DocumentController::class, 'toggleVisibility'])->name('bookings.documents.toggle_visibility');
    Route::delete('/bookings/{booking}/documents/{document}', [DocumentController::class, 'destroy'])->name('bookings.documents.destroy');

    // 4. Tour Templates & Blueprint Days (Admin, Agent)
    Route::middleware(['role:admin,agent'])->group(function () {
        Route::resource('tours', TourController::class);
        Route::post('/tours/{tour}/days', [TourDayController::class, 'store'])->name('tours.days.store');
        Route::put('/tours/{tour}/days/{tourDay}', [TourDayController::class, 'update'])->name('tours.days.update');
        Route::delete('/tours/{tour}/days/{tourDay}', [TourDayController::class, 'destroy'])->name('tours.days.destroy');
    });

    // 5. Fleet & Field Operations (Admin, Agent)
    Route::middleware(['role:admin,agent'])->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers', DriverController::class);
        Route::resource('guides', GuideController::class);

        Route::get('/resources/calendar', [ResourceCalendarController::class, 'index'])->name('resources.calendar');
    });

    // 6. Accounting & Finance (Admin, Accountant)
    Route::middleware(['role:admin,accountant'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

        Route::get('/exchange-rates', [ExchangeRateController::class, 'index'])->name('exchange-rates.index');
        Route::post('/exchange-rates', [ExchangeRateController::class, 'update'])->name('exchange-rates.update');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-monthly', [ReportController::class, 'exportMonthly'])->name('reports.export_monthly');
        Route::get('/reports/export-bookings-profit', [ReportController::class, 'exportBookingsProfit'])->name('reports.export_bookings_profit');
        Route::get('/reports/export-payments', [ReportController::class, 'exportPayments'])->name('reports.export_payments');
        Route::get('/reports/audit-logs', [ReportController::class, 'auditLogs'])->name('reports.audit_logs');
    });

    // 7. System Administration & Staff Accounts (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
