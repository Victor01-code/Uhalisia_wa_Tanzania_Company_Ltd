<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('tour_id')->nullable()->constrained('tours')->nullOnDelete();
            $table->string('title');
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);
            $table->enum('status', [
                'inquiry',
                'quote_sent',
                'confirmed',
                'paid_in_full',
                'in_progress',
                'completed',
                'cancelled',
            ])->default('inquiry')->index();
            $table->enum('payment_mode', ['company_handles', 'client_direct'])->default('company_handles')->index();
            $table->decimal('total_price_usd', 10, 2)->default(0.00);
            $table->decimal('discount_usd', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->string('public_token', 64)->unique()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('booking_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->unsignedInteger('day_number')->default(1);
            $table->date('date')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('destination')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('meals')->nullable();
            $table->text('activities')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_days');
        Schema::dropIfExists('bookings');
    }
};
