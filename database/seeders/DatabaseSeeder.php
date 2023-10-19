<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            StateSeeder::class,
            GeneralSettingSeeder::class,
            PeakErrorDetailSeed::class,
            PermissionSeeder::class,
            ProductSeeder::class,
            PlanDetailSeeder::class,
            MedicineVarientSeeder::class
        ]);
    }
}
