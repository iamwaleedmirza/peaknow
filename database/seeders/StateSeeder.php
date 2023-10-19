<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            ['Alaska', 'AK', 'America'],
            ['Alabama', 'AL', 'America'],
            ['Arkansas', 'AR', 'America'],
            ['Arizona', 'AZ', 'America'],
            ['California', 'CA', 'America'],
            ['Colorado', 'CO', 'America'],
            ['Connecticut', 'CT', 'America'],
            ['District of Columbia', 'DC', 'America'],
            ['Delaware', 'DE', 'America'],
            ['Florida', 'FL', 'America'],
            ['Georgia', 'GA', 'America'],
            ['Hawaii', 'HI', 'America'],
            ['Iowa', 'IA', 'America'],
            ['Idaho', 'ID', 'America'],
            ['Illinois', 'IL', 'America'],
            ['Indiana', 'IN', 'America'],
            ['Kansas', 'KS', 'America'],
            ['Kentucky', 'KY', 'America'],
            ['Louisiana', 'LA', 'America'],
            ['Massachusetts', 'MA', 'America'],
            ['Maryland', 'MD', 'America'],
            ['Maine', 'ME', 'America'],
            ['Michigan', 'MI', 'America'],
            ['Minnesota', 'MN', 'America'],
            ['Missouri', 'MO', 'America'],
            ['Mississippi', 'MS', 'America'],
            ['Montana', 'MT', 'America'],
            ['North Carolina', 'NC', 'America'],
            ['North Dakota', 'ND', 'America'],
            ['Nebraska', 'NE', 'America'],
            ['New Hampshire', 'NH', 'America'],
            ['New Jersey', 'NJ', 'America'],
            ['New Mexico', 'NM', 'America'],
            ['Nevada', 'NV', 'America'],
            ['New York', 'NY', 'America'],
            ['Ohio', 'OH', 'America'],
            ['Oklahoma', 'OK', 'America'],
            ['Oregon', 'OR', 'America'],
            ['Pennsylvania', 'PA', 'America'],
            ['Rhode Island', 'RI', 'America'],
            ['South Carolina', 'SC', 'America'],
            ['South Dakota', 'SD', 'America'],
            ['Tennessee', 'TN', 'America'],
            ['Texas', 'TX', 'America'],
            ['Utah', 'UT', 'America'],
            ['Virginia', 'VA', 'America'],
            ['Vermont', 'VT', 'America'],
            ['Washington', 'WA', 'America'],
            ['Wisconsin', 'WI', 'America'],
            ['West Virginia', 'WV', 'America'],
            ['Wyoming', 'WY', 'America']
        ];

        foreach ($states as $state) {
            DB::table('states')->insert([
                'state' => $state[0],
                'state_code' => $state[1],
                'country' => $state[2],
            ]);
        }
    }
}
