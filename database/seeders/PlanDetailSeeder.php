<?php

namespace Database\Seeders;

use App\Models\PlanDetail;
use Illuminate\Database\Seeder;

class PlanDetailSeeder extends Seeder
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
                'name' => 'Peaks Performer',
                'subscription_type' => 1,
            ],
            [
                'name' => 'Peaks Warrior',
                'subscription_type' => 0,
            ]
        ];

        foreach ($plans as $plan) {
            PlanDetail::create($plan);
        }
    }
}
