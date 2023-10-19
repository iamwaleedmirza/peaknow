<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generalSetting = [
            'site_title' => 'PeaksCurative',
            'consultation_fee' => 30,
            'support_mail' => 'techsupport@peakscurative.com',
            'allowed_states' => 'Florida,New York,Virginia,Illinois,Alabama,Puerto Rico,Punjab,Gujarat',
        ];
        GeneralSetting::create($generalSetting);
    }
}
