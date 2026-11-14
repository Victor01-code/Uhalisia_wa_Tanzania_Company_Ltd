<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->date('expense_date')->index();
            $table->enum('category', [
                'fuel',
                'driver',
                'park_fees',
                'accommodation',
                'vehicle_service',
                'vehicle_checkup',
                'insurance',
                'licences',
                'tax',
                'other',
            ])->index();
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->enum('currency', ['USD', 'TZS'])->default('TZS')->index();
            $table->decimal('amount_tzs', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'expense_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
