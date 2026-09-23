<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 0;

        foreach (config('sabonea.locales') as $code => $name) {
            Language::query()->firstOrCreate(['code' => $code], [
                'name' => $name,
                'is_active' => true,
                'is_default' => $code === config('sabonea.reference_locale'),
                'sort' => $sort++,
            ]);
        }
    }
}
