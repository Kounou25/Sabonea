<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Devenir fournisseur" buttons used to open the contact page: they now open the supplier form.
     */
    public function up(): void
    {
        $this->repoint('contact', 'devenir-fournisseur');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->repoint('devenir-fournisseur', 'contact');
    }

    private function repoint(string $from, string $to): void
    {
        foreach (['page_sections', 'hero_slides'] as $table) {
            foreach (['cta' => 'cta_url', 'cta2' => 'cta2_url'] as $prefix => $urlColumn) {
                DB::table($table)
                    ->where($urlColumn, $from)
                    ->where("{$prefix}_label->fr", 'Devenir fournisseur')
                    ->update([$urlColumn => $to]);
            }
        }
    }
};
