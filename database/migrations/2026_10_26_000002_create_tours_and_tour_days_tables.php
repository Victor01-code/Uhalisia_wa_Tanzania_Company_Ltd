<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->enum('type', ['safari', 'climb', 'beach', 'cultural', 'combo'])->default('safari')->index();
            $table->unsignedInteger('days')->default(1);
            $table->text('summary')->nullable();
            $table->decimal('base_price_usd', 10, 2)->default(0.00);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('tour_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->unsignedInteger('day_number')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('destination')->nullable();
            $table->string('accommodation_note')->nullable();
            $table->string('meals')->nullable();
            $table->text('activities')->nullable();
            $table->timestamps();

            $table->index(['tour_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_days');
        Schema::dropIfExists('tours');
    }
};
