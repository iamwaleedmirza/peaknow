<?php

namespace Database\Seeders;

use App\Models\MedicineVarient;
use Illuminate\Database\Seeder;

class MedicineVarientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            ['name' => 'Gummies'],
            ['name' => 'Tablets'],
            ['name' => 'Capsules'],
        ];

        foreach ($plans as $plan) {
            MedicineVarient::create($plan);
        }
    }
}
