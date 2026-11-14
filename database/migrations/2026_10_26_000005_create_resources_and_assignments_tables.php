<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('plate_number')->unique();
            $table->string('type')->default('Safari Land Cruiser');
            $table->unsignedInteger('seats')->default(7);
            $table->boolean('active')->default(true)->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('phone')->nullable();
            $table->string('languages')->default('English, Swahili');
            $table->string('licence_number')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('phone')->nullable();
            $table->string('languages')->default('English, Swahili');
            $table->string('permit_number')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->enum('resource_type', ['vehicle', 'driver', 'guide'])->index();
            $table->unsignedBigInteger('resource_id')->index();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->timestamps();

            $table->index(['resource_type', 'resource_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('guides');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('vehicles');
    }
};
