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
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->jsonb('eyebrow')->nullable();
            $table->jsonb('title');
            $table->jsonb('text')->nullable();
            $table->string('image');
            $table->jsonb('image_alt')->nullable();
            $table->jsonb('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->jsonb('cta2_label')->nullable();
            $table->string('cta2_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
