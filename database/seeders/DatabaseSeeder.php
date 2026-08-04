<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            HostelSeeder::class,
            RoomSeeder::class,
            UserSeeder::class,
            HostelApplicationSeeder::class,
            AllocationSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
