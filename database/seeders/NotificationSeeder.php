<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $chidera = User::where('matric_number', 'SUMAS/22/1045')->first();
        $admin = Admin::where('email', 'admin@sumas.edu.ng')->first();

        if ($chidera) {
            $studentNotifs = [
                ['title' => 'Application Approved!', 'message' => 'Your hostel application has been approved and Room A-112 assigned.', 'type' => 'success', 'days' => 10, 'unread' => true],
                ['title' => 'Hostel Inspection Notice', 'message' => 'A routine inspection is scheduled for July 22. Please ensure your space is tidy.', 'type' => 'info', 'days' => 2, 'unread' => true],
                ['title' => 'Scheduled Maintenance', 'message' => 'Water supply maintenance in Female Hostel A on July 10, 9am-12pm.', 'type' => 'warning', 'days' => 3, 'unread' => true],
                ['title' => 'Application Under Review', 'message' => 'Your application is being reviewed by the Hostel Office.', 'type' => 'info', 'days' => 15, 'unread' => false],
                ['title' => 'Application Submitted', 'message' => 'Your application for Female Hostel A was received.', 'type' => 'success', 'days' => 18, 'unread' => false],
                ['title' => 'Welcome to SUMAS Hostels!', 'message' => 'Your student account was created successfully.', 'type' => 'info', 'days' => 20, 'unread' => false],
            ];

            foreach ($studentNotifs as $n) {
                SystemNotification::create([
                    'user_id' => $chidera->id,
                    'title' => $n['title'],
                    'message' => $n['message'],
                    'type' => $n['type'],
                    'created_at' => now()->subDays($n['days']),
                    'updated_at' => now()->subDays($n['days']),
                    'read_at' => $n['unread'] ? null : now()->subDays($n['days'] - 1),
                ]);
            }
        }

        if ($admin) {
            $adminNotifs = [
                ['title' => 'Application Approved', 'message' => "Emeka Okafor's application was approved and room B-204 assigned.", 'type' => 'success', 'minutes' => 12, 'unread' => true],
                ['title' => 'Block Nearing Capacity', 'message' => 'Male Hostel B has reached 91% occupancy.', 'type' => 'warning', 'minutes' => 60, 'unread' => true],
                ['title' => 'Application Rejected', 'message' => 'An application was flagged as a duplicate entry.', 'type' => 'danger', 'minutes' => 180, 'unread' => true],
                ['title' => 'New Registration', 'message' => 'Faith Adeyemi created a new student account.', 'type' => 'info', 'minutes' => 300, 'unread' => true],
                ['title' => 'Bulk Approval', 'message' => 'Several applications approved for Female Hostel Block B.', 'type' => 'success', 'minutes' => 1400, 'unread' => true],
                ['title' => 'System Update', 'message' => 'Room capacity settings updated for Postgraduate Annex.', 'type' => 'info', 'minutes' => 2880, 'unread' => false],
            ];

            foreach ($adminNotifs as $n) {
                SystemNotification::create([
                    'admin_id' => $admin->id,
                    'title' => $n['title'],
                    'message' => $n['message'],
                    'type' => $n['type'],
                    'created_at' => now()->subMinutes($n['minutes']),
                    'updated_at' => now()->subMinutes($n['minutes']),
                    'read_at' => $n['unread'] ? null : now()->subMinutes($n['minutes'] - 5),
                ]);
            }
        }
    }
}
