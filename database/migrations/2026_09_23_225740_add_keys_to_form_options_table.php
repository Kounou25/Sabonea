<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Answers are stored as fixed technical keys; labels stay translatable and editable.
     */
    public function up(): void
    {
        Schema::table('form_options', function (Blueprint $table) {
            $table->string('key', 50)->nullable()->after('field');
            $table->boolean('is_other')->default(false)->after('label');
            $table->boolean('is_exclusive')->default(false)->after('is_other');
            $table->unique(['field', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_options', function (Blueprint $table) {
            $table->dropUnique(['field', 'key']);
            $table->dropColumn(['key', 'is_other', 'is_exclusive']);
        });
    }
};
