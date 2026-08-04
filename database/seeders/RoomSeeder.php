<?php

namespace Database\Seeders;

use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // [hostel name => [room count, capacity per room, floors]]
        $config = [
            'Male Hostel - Block A' => ['rooms' => 45, 'capacity' => 4],
            'Male Hostel - Block B' => ['rooms' => 40, 'capacity' => 4],
            'Male Hostel - Block C' => ['rooms' => 30, 'capacity' => 4],
            'Female Hostel - Block A' => ['rooms' => 50, 'capacity' => 4],
            'Female Hostel - Block B' => ['rooms' => 45, 'capacity' => 4],
            'Postgraduate Annex' => ['rooms' => 32, 'capacity' => 2],
        ];

        $floors = ['Ground Floor', '1st Floor', '2nd Floor', '3rd Floor'];
        $prefixMap = [
            'Male Hostel - Block A' => 'A',
            'Male Hostel - Block B' => 'B',
            'Male Hostel - Block C' => 'C',
            'Female Hostel - Block A' => 'A',
            'Female Hostel - Block B' => 'B',
            'Postgraduate Annex' => 'PG',
        ];

        foreach ($config as $name => $cfg) {
            $hostel = Hostel::where('name', $name)->first();
            if (! $hostel) {
                continue;
            }

            $prefix = $prefixMap[$name];

            for ($i = 1; $i <= $cfg['rooms']; $i++) {
                $roomNumber = $prefix.'-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT);
                $floor = $floors[intdiv($i - 1, 15) % count($floors)];

                Room::updateOrCreate(
                    ['hostel_id' => $hostel->id, 'room_number' => $roomNumber],
                    [
                        'floor' => $floor,
                        'capacity' => $cfg['capacity'],
                        'status' => 'vacant',
                    ]
                );
            }
        }
    }
}
