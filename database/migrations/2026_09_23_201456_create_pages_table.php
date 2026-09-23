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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->jsonb('meta_title');
            $table->jsonb('meta_description')->nullable();
            $table->jsonb('meta_keywords')->nullable();
            $table->boolean('noindex')->default(false);
            $table->jsonb('header_title')->nullable();
            $table->jsonb('breadcrumb')->nullable();
            $table->string('header_image')->nullable();
            $table->jsonb('header_image_alt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
