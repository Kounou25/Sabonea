<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sectors and equipment types become the shared reference lists of every form:
     * a fixed technical key, a label for the forms, and a flag for the public pages.
     */
    public function up(): void
    {
        foreach (['sectors', 'equipment_types'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('key', 50)->nullable()->unique()->after('id');
                $table->jsonb('form_label')->nullable()->after('name');
                $table->boolean('is_other')->default(false)->after('is_active');
                $table->boolean('show_on_site')->default(true)->after('is_other');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['sectors', 'equipment_types'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropUnique(['key']);
                $table->dropColumn(['key', 'form_label', 'is_other', 'show_on_site']);
            });
        }
    }
};
