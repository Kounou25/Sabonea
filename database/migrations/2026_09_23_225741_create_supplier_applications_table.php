<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per supplier: the contact form (form 1), then the private onboarding form (form 2).
     * Answers are stored as jsonb keyed by the technical keys of the specification.
     */
    public function up(): void
    {
        Schema::create('supplier_applications', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new')->index();
            $table->boolean('is_test')->default(false)->index();

            // Copied from the answers for lists and searches.
            $table->string('company_name');
            $table->string('country', 2)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            $table->jsonb('contact_answers');
            $table->string('contact_locale', 5);
            $table->timestamp('contact_submitted_at')->nullable();

            $table->string('onboarding_token', 64)->nullable()->unique();
            $table->timestamp('onboarding_token_expires_at')->nullable();
            $table->jsonb('onboarding_answers')->nullable();
            $table->string('onboarding_locale', 5)->nullable();
            $table->timestamp('onboarding_started_at')->nullable();
            $table->timestamp('onboarding_saved_at')->nullable();
            $table->timestamp('onboarding_submitted_at')->nullable();

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
        Schema::dropIfExists('supplier_applications');
    }
};
