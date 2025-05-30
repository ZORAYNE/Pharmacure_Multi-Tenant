<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CentralAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Central Admin',
            'email' => 'admin@central.com',
            'password' => Hash::make('password123'),  // Change as needed
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
