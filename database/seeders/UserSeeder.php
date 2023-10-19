<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for admin user
        $adminUser = [
            'first_name' => 'Peaks',
            'last_name' => 'Curative',
            'email' => 'techsupport@peakscurative.com',
            'password' => Hash::make('Test@123'),
            'u_type' => 'superadmin',
            'phone_verified' => 1,
            'email_verified' => 1,
            'firstsiginup' => 0,
        ];
        User::create($adminUser);
    }
}
