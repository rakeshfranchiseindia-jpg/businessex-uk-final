<?php

namespace Database\Seeders;

use App\Models\Admin\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates (or resets) a super admin for local development.
 *
 *   php artisan db:seed --class=AdminUserSeeder
 *
 * Override the defaults with ADMIN_SEED_EMAIL / ADMIN_SEED_PASSWORD in .env.
 * Never run this against production.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL', 'admin@businessx.test');

        AdminUser::query()->updateOrCreate(
            ['admin_email' => $email],
            [
                'admin_name' => 'Local Admin',
                'admin_password' => Hash::make(env('ADMIN_SEED_PASSWORD', 'Admin@12345')),
                'admin_dept' => 'Development',
                'admin_role' => 1,
                'admin_is_active' => 1,
            ],
        );

        $this->command?->info("Super admin ready: {$email}");
    }
}
