<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@sumas.edu.ng'],
            [
                'name' => 'Adaeze Okoro',
                'phone' => '0803 555 0192',
                'staff_id' => 'SUMAS-ST-0021',
                'role' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'warden@sumas.edu.ng'],
            [
                'name' => 'Paul Eze',
                'phone' => '0805 222 4411',
                'staff_id' => 'SUMAS-ST-0042',
                'role' => 'Hostel Warden',
                'password' => Hash::make('password'),
            ]
        );
    }
}
