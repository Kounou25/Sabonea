<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'contact_email' => 'contact@sabonea.com',
            'notification_email' => 'contact@sabonea.com',
            'linkedin_url' => 'https://www.linkedin.com/company/sabonea',
            'linkedin_label' => '@sabonea',
            'instagram_url' => 'https://www.instagram.com/sabonea_group',
            'instagram_label' => '@sabonea_group',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61594208143644',
            'facebook_label' => 'Sabonea',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
