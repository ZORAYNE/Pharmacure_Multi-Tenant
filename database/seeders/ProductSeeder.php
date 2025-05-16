<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::connection('tenant')->table('products')->insert([
            [
                'name' => 'Product A',
                'price' => 10.99,
                'quantity' => 100,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Product B',
                'price' => 15.50,
                'quantity' => 50,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Product C',
                'price' => 7.25,
                'quantity' => 200,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Product D',
                'price' => 20.00,
                'quantity' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Product E',
                'price' => 5.75,
                'quantity' => 150,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
