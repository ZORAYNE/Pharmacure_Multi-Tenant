<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $expirationDate = $now->copy()->addYear();

        // Seed only to tenant database connection
        $tenantConnection = DB::connection('tenant');

        // Output the database name to verify connection
        echo 'Seeding products to database: ' . $tenantConnection->getDatabaseName() . PHP_EOL;

        $tenantConnection->table('products')->truncate();

        for ($i = 1; $i <= 100; $i++) {
            $tenantConnection->table('products')->insert([
                'name' => 'Product ' . $i,
                'brand' => 'Brand ' . $i,
                'price' => mt_rand(100, 10000) / 100, // random price between 1.00 and 100.00
                'stock_quantity' => 50,
                'expiration_date' => $expirationDate,
                'picture' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
