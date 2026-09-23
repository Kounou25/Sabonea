<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('need_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('equipment_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('deadline_option_id')->nullable()->constrained('form_options')->nullOnDelete();
            $table->text('message')->nullable();
            $table->string('locale', 5);
            $table->string('status')->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('need_requests');
    }
};
