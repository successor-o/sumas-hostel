<?php

namespace Database\Seeders;

use App\Models\Hostel;
use Illuminate\Database\Seeder;

class HostelSeeder extends Seeder
{
    public function run(): void
    {
        $hostels = [
            [
                'name' => 'Male Hostel - Block A',
                'category' => 'Male',
                'warden' => 'Mr. Paul Eze',
                'description' => '2-storey block with 4-in-a-room accommodation, shared bathrooms per floor, and a resident warden\'s office at the entrance.',
                'image' => 'male-hostel.jpg',
                'status' => 'Active',
            ],
            [
                'name' => 'Male Hostel - Block B',
                'category' => 'Male',
                'warden' => 'Mr. Sunday Ike',
                'description' => 'Standard 4-in-a-room block located beside the reading centre.',
                'image' => 'male-hostel.jpg',
                'status' => 'Active',
            ],
            [
                'name' => 'Male Hostel - Block C',
                'category' => 'Male',
                'warden' => 'Mr. Chuka Nnamdi',
                'description' => 'Compact 4-in-a-room block, currently at full capacity.',
                'image' => 'male-hostel.jpg',
                'status' => 'Active',
            ],
            [
                'name' => 'Female Hostel - Block A',
                'category' => 'Female',
                'warden' => 'Mrs. Grace Nwankwo',
                'description' => 'Gated block with a dedicated matron\'s office, visiting-hours desk, and enclosed compound.',
                'image' => 'female-hostel.jpg',
                'status' => 'Active',
            ],
            [
                'name' => 'Female Hostel - Block B',
                'category' => 'Female',
                'warden' => 'Mrs. Ifeoma Obi',
                'description' => 'Newer block with the most beds currently open.',
                'image' => 'female-hostel.jpg',
                'status' => 'Active',
            ],
            [
                'name' => 'Postgraduate Annex',
                'category' => 'Postgraduate',
                'warden' => 'Dr. Uche Madu',
                'description' => 'Self-contained 2-in-a-room accommodation for postgraduate students.',
                'image' => 'campus-building.jpg',
                'status' => 'Active',
            ],
        ];

        foreach ($hostels as $hostel) {
            Hostel::updateOrCreate(['name' => $hostel['name']], $hostel);
        }
    }
}
