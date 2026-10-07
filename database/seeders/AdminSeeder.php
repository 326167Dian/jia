<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => env('ADMIN_SEED_EMAIL', 'admin@mysifa.local')],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(env('ADMIN_SEED_PASSWORD', 'mysifa12345')),
            ]
        );
    }
}
