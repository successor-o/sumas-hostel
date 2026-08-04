<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'name' => 'Chidera Nwosu',
                'matric_number' => 'SUMAS/22/1045',
                'email' => 'c.nwosu@sumas.edu.ng',
                'phone' => '0803 123 4567',
                'faculty' => 'Allied Health Sciences',
                'level' => '300 Level',
                'gender' => 'Female',
                'emergency_contact_name' => 'Mrs. Grace Nwosu',
                'emergency_contact_phone' => '0806 987 6543',
            ],
            [
                'name' => 'Emeka Okafor',
                'matric_number' => 'SUMAS/21/0876',
                'email' => 'e.okafor@sumas.edu.ng',
                'phone' => '0802 456 7890',
                'faculty' => 'Clinical Medicine',
                'level' => '400 Level',
                'gender' => 'Male',
            ],
            [
                'name' => 'Faith Adeyemi',
                'matric_number' => 'SUMAS/23/1290',
                'email' => 'f.adeyemi@sumas.edu.ng',
                'phone' => '0807 111 2233',
                'faculty' => 'Basic Medical Sciences',
                'level' => '100 Level',
                'gender' => 'Female',
            ],
            [
                'name' => 'Ifeanyi Chukwu',
                'matric_number' => 'SUMAS/20/0456',
                'email' => 'i.chukwu@sumas.edu.ng',
                'phone' => '0809 333 4455',
                'faculty' => 'Applied Sciences',
                'level' => '500 Level',
                'gender' => 'Male',
            ],
            [
                'name' => 'Blessing Uche',
                'matric_number' => 'SUMAS/22/1502',
                'email' => 'b.uche@sumas.edu.ng',
                'phone' => '0813 777 8899',
                'faculty' => 'Clinical Medicine',
                'level' => 'Postgraduate',
                'gender' => 'Female',
            ],
            [
                'name' => 'Tochukwu Obi',
                'matric_number' => 'SUMAS/24/1987',
                'email' => 't.obi@sumas.edu.ng',
                'phone' => '0816 444 5566',
                'faculty' => 'Applied Sciences',
                'level' => '100 Level',
                'gender' => 'Male',
            ],
            [
                'name' => 'Kelechi James',
                'matric_number' => 'SUMAS/23/0765',
                'email' => 'k.james@sumas.edu.ng',
                'phone' => '0818 999 0011',
                'faculty' => 'Allied Health Sciences',
                'level' => '200 Level',
                'gender' => 'Male',
            ],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['matric_number' => $student['matric_number']],
                array_merge($student, ['password' => Hash::make('password'), 'status' => 'approved'])
            );
        }
    }
}
