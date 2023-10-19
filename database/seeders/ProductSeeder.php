<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'name' => 'Tadalafil',
            ],
            [
                'name' => 'Sildenafil',
            ]
        ];

        foreach ($plans as $plan) {
            Product::create($plan);
        }
    }
}
