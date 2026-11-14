<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedTinyInteger('month')->index();
            $table->decimal('tzs_per_usd', 12, 2);
            $table->timestamps();

            $table->unique(['year', 'month']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->date('paid_on')->index();
            $table->decimal('amount', 12, 2);
            $table->enum('currency', ['USD', 'TZS'])->default('USD')->index();
            $table->decimal('exchange_rate_used', 12, 2);
            $table->decimal('amount_usd', 12, 2);
            $table->decimal('amount_tzs', 14, 2);
            $table->enum('method', ['cash', 'bank', 'mobile_money', 'card', 'other'])->default('bank')->index();
            $table->string('reference')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->enum('type', ['deposit', 'balance', 'refund', 'other'])->default('deposit')->index();
            $table->timestamps();

            $table->index(['booking_id', 'paid_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('exchange_rates');
    }
};
