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
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->string('name');
            $table->jsonb('fields');
            $table->jsonb('item_fields')->nullable();
            $table->jsonb('eyebrow')->nullable();
            $table->jsonb('title')->nullable();
            $table->jsonb('subtitle')->nullable();
            $table->jsonb('body')->nullable();
            $table->jsonb('note')->nullable();
            $table->string('image')->nullable();
            $table->jsonb('image_alt')->nullable();
            $table->jsonb('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->jsonb('cta2_label')->nullable();
            $table->string('cta2_url')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unique(['page_id', 'key']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
