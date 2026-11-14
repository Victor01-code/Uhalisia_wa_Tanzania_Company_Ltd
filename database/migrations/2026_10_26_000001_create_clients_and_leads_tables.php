<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->index();
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('name')->index();
            $table->string('email')->index();
            $table->string('country')->nullable();
            $table->text('safari_interest')->nullable();
            $table->text('message')->nullable();
            $table->enum('source', ['website_quote_form', 'book_form', 'manual'])->default('manual')->index();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->enum('status', ['new', 'contacted', 'quoted', 'won', 'lost'])->default('new')->index();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('clients');
    }
};
