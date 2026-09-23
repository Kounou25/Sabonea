<?php

namespace Database\Seeders;

use App\Models\UiTranslation;
use Database\Seeders\Concerns\SeedsTranslatedContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UiTranslationSeeder extends Seeder
{
    use SeedsTranslatedContent;

    /**
     * Only missing keys are created: texts edited from the back-office are never overwritten.
     */
    public function run(): void
    {
        foreach (require __DIR__.'/data/ui.php' as $key => $texts) {
            UiTranslation::query()->firstOrCreate(['key' => $key], [
                'group' => Str::before($key, '.'),
                'text' => $this->tr($texts),
            ]);
        }
    }
}
