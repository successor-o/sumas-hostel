<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Allocation;
use App\Models\Hostel;
use App\Models\HostelApplication;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class AllocationSeeder extends Seeder
{
    public function run(): void
    {
        $session = config('sumas.session', '2026/2027');
        $admin = Admin::where('email', 'admin@sumas.edu.ng')->first();

        // Manually pin fixed room/bed combos for the named demo applications
        // so the story told across dashboard, allocation & slip pages matches.
        $fixed = [
            'SUMAS/22/1045' => ['room' => 'A-112', 'bed' => 2],
            'SUMAS/21/0876' => ['room' => 'B-204', 'bed' => 3],
            'SUMAS/22/1502' => ['room' => 'PG-032', 'bed' => 1],
        ];

        $approved = HostelApplication::approved()->with(['user', 'hostel'])->get();

        foreach ($approved as $application) {
            $roomNumber = $fixed[$application->user->matric_number]['room'] ?? null;
            $bed = $fixed[$application->user->matric_number]['bed'] ?? 1;

            $room = $roomNumber
                ? Room::where('hostel_id', $application->hostel_id)->where('room_number', $roomNumber)->first()
                : Room::where('hostel_id', $application->hostel_id)->where('status', '!=', 'maintenance')->first();

            if (! $room) {
                continue;
            }

            Allocation::updateOrCreate(
                ['user_id' => $application->user_id, 'session' => $session],
                [
                    'hostel_id' => $application->hostel_id,
                    'room_id' => $room->id,
                    'hostel_application_id' => $application->id,
                    'bed_number' => $bed,
                    'status' => 'active',
                    'allocated_by' => $admin?->id,
                    'allocated_at' => now()->subDays(rand(1, 5)),
                ]
            );

            $room->refreshStatus();
        }

        // Populate the remaining beds with real seeded students + allocation
        // rows (rather than a denormalized counter) so every occupancy
        // figure across the app -- dashboards, reports, occupancy monitor --
        // is computed consistently from the same relational data.
        $this->bulkFillRemainingCapacity($session, $admin);
    }

    private function bulkFillRemainingCapacity(string $session, ?Admin $admin): void
    {
        $targets = [
            'Male Hostel - Block A' => 0.68,
            'Male Hostel - Block B' => 0.91,
            'Male Hostel - Block C' => 1.00,
            'Female Hostel - Block A' => 0.89,
            'Female Hostel - Block B' => 0.54,
            'Postgraduate Annex' => 1.00,
        ];

        foreach ($targets as $hostelName => $targetPct) {
            $hostel = Hostel::where('name', $hostelName)->first();
            if (! $hostel) {
                continue;
            }

            $genderFactory = $hostel->category === 'Male' ? 'male' : ($hostel->category === 'Female' ? 'female' : null);

            $rooms = $hostel->rooms()->orderBy('id')->get();
            $totalBeds = $rooms->sum('capacity');
            $targetOccupied = (int) round($totalBeds * $targetPct);
            $alreadyOccupied = $hostel->occupiedBeds();
            $remainingToFill = max(0, $targetOccupied - $alreadyOccupied);

            foreach ($rooms as $room) {
                if ($remainingToFill <= 0) {
                    break;
                }

                $freeInRoom = $room->availableBeds();
                if ($freeInRoom <= 0) {
                    continue;
                }

                // Leave a handful of completely vacant rooms per block so the
                // "New Allocation" admin flow always has somewhere to place
                // a freshly approved student in the live demo.
                if ($room->occupiedBeds() === 0 && $remainingToFill < $freeInRoom && $remainingToFill < $totalBeds * 0.15) {
                    continue;
                }

                $fillHere = min($freeInRoom, $remainingToFill);
                $usedBeds = range(1, $room->capacity);
                $takenBeds = $room->activeAllocations()->pluck('bed_number')->all();
                $openBeds = array_values(array_diff($usedBeds, $takenBeds));

                for ($i = 0; $i < $fillHere; $i++) {
                    $factory = User::factory();
                    if ($genderFactory) {
                        $factory = $factory->{$genderFactory}();
                    }
                    $student = $factory->create();

                    $application = HostelApplication::create([
                        'user_id' => $student->id,
                        'hostel_id' => $hostel->id,
                        'reason' => 'Live far from campus',
                        'status' => 'approved',
                        'reviewed_by' => $admin?->id,
                        'reviewed_at' => now()->subDays(rand(2, 10)),
                        'session' => $session,
                    ]);

                    Allocation::create([
                        'user_id' => $student->id,
                        'hostel_id' => $hostel->id,
                        'room_id' => $room->id,
                        'hostel_application_id' => $application->id,
                        'bed_number' => $openBeds[$i] ?? ($i + 1),
                        'session' => $session,
                        'status' => 'active',
                        'allocated_by' => $admin?->id,
                        'allocated_at' => now()->subDays(rand(1, 9)),
                    ]);
                }

                $room->refreshStatus();
                $remainingToFill -= $fillHere;
            }
        }
    }
}
