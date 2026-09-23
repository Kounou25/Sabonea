<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Support\Locales;
use App\Support\Ui;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            SettingSeeder::class,
            UiTranslationSeeder::class,
            CatalogSeeder::class,
            PageSeeder::class,
            AdminUserSeeder::class,
        ]);

        Locales::flush();
        Ui::flush();
        Cache::forget(Setting::CACHE_KEY);
    }
}
