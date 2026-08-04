<?php

namespace Database\Seeders;

use App\Models\Hostel;
use App\Models\HostelApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class HostelApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $session = config('sumas.session', '2026/2027');

        $rows = [
            ['matric' => 'SUMAS/22/1045', 'hostel' => 'Female Hostel - Block A', 'status' => 'approved', 'reason' => 'Live far from campus'],
            ['matric' => 'SUMAS/21/0876', 'hostel' => 'Male Hostel - Block B', 'status' => 'approved', 'reason' => 'Security concerns off-campus'],
            ['matric' => 'SUMAS/22/1502', 'hostel' => 'Postgraduate Annex', 'status' => 'approved', 'reason' => 'Live far from campus'],
            ['matric' => 'SUMAS/20/0456', 'hostel' => 'Male Hostel - Block A', 'status' => 'rejected', 'reason' => 'Live far from campus', 'rejection_reason' => 'Duplicate application'],
            ['matric' => 'SUMAS/23/1290', 'hostel' => 'Female Hostel - Block B', 'status' => 'pending', 'reason' => 'Medical / accessibility need'],
            ['matric' => 'SUMAS/24/1987', 'hostel' => 'Male Hostel - Block B', 'status' => 'pending', 'reason' => 'Live far from campus'],
            ['matric' => 'SUMAS/23/0765', 'hostel' => 'Male Hostel - Block A', 'status' => 'pending', 'reason' => 'Security concerns off-campus'],
        ];

        foreach ($rows as $row) {
            $user = User::where('matric_number', $row['matric'])->first();
            $hostel = Hostel::where('name', $row['hostel'])->first();

            if (! $user || ! $hostel) {
                continue;
            }

            HostelApplication::updateOrCreate(
                ['user_id' => $user->id, 'session' => $session],
                [
                    'hostel_id' => $hostel->id,
                    'reason' => $row['reason'],
                    'status' => $row['status'],
                    'rejection_reason' => $row['rejection_reason'] ?? null,
                    'reviewed_at' => $row['status'] === 'pending' ? null : now()->subDays(rand(1, 6)),
                ]
            );
        }
    }
}
