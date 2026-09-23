<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * First administrator account, from ADMIN_EMAIL / ADMIN_PASSWORD (a random password is generated when empty).
     */
    public function run(): void
    {
        $email = config('sabonea.admin.email');

        if (User::query()->where('email', $email)->exists()) {
            return;
        }

        $configuredPassword = config('sabonea.admin.password');
        $password = $configuredPassword ?: Str::password(16, symbols: false);

        User::create([
            'name' => 'Administrateur',
            'email' => $email,
            'password' => $password,
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);

        if (! $configuredPassword) {
            $this->command?->warn("Compte administrateur créé : {$email} / {$password} (à changer après la première connexion)");
        }
    }
}
