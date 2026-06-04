<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PoliticalPartySeeder::class);

        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'full_name' => 'Tume Huru ya Taifa ya Uchaguzi Tanzania - Admin',
                'email' => 'admin@tume.go.tz',
                'phone' => '+255712345678',
                'nida_number' => '19900101-00001-00001-00',
                'password' => 'Admin@123',
                'role' => 'admin',
                'status' => 'active',
                'language' => 'en',
                'age' => 36,
                'is_verified' => true,
                'email_verified' => true,
            ]);
        }
    }
}
