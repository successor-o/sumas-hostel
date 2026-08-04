<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Allocation;
use App\Models\Hostel;
use App\Models\HostelApplication;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::factory()->create();
    }

    public function test_admin_can_create_update_and_delete_student(): void
    {
        $this->actingAs($this->admin(), 'admin');

        $this->post(route('admin.students.store'), [
            'name' => 'Ada Test', 'matric_number' => 'SUMAS/25/0001', 'email' => 'ada@sumas.edu.ng',
            'phone' => '08010000000', 'faculty' => 'Clinical Medicine', 'level' => '100 Level', 'gender' => 'Female',
        ])->assertRedirect();

        $student = User::where('matric_number', 'SUMAS/25/0001')->firstOrFail();

        $this->put(route('admin.students.update', $student), [
            'name' => 'Ada Test Updated', 'matric_number' => 'SUMAS/25/0001', 'email' => 'ada@sumas.edu.ng',
            'phone' => '08020000000', 'faculty' => 'Applied Sciences', 'level' => '200 Level',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $student->id, 'name' => 'Ada Test Updated', 'level' => '200 Level']);

        $this->delete(route('admin.students.destroy', $student))->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
    }

    public function test_admin_can_create_update_and_delete_hostel(): void
    {
        $this->actingAs($this->admin(), 'admin');

        $this->post(route('admin.hostels.store'), [
            'name' => 'Male Hostel - Test Block', 'category' => 'Male', 'warden' => 'Mr. Test',
            'description' => 'A test block.',
        ])->assertRedirect();

        $hostel = Hostel::where('name', 'Male Hostel - Test Block')->firstOrFail();

        $this->put(route('admin.hostels.update', $hostel), [
            'name' => 'Male Hostel - Test Block', 'category' => 'Male', 'warden' => 'Mr. Test',
            'status' => 'Under Maintenance', 'description' => 'A test block.',
        ])->assertRedirect();

        $this->assertDatabaseHas('hostels', ['id' => $hostel->id, 'status' => 'Under Maintenance']);

        $this->delete(route('admin.hostels.destroy', $hostel))->assertRedirect();

        $this->assertDatabaseMissing('hostels', ['id' => $hostel->id]);
    }

    public function test_admin_can_create_update_and_delete_room(): void
    {
        $this->actingAs($this->admin(), 'admin');

        $hostel = Hostel::create(['name' => 'Male Hostel - Test', 'category' => 'Male', 'status' => 'Active']);

        $this->post(route('admin.rooms.store'), [
            'hostel_id' => $hostel->id, 'room_number' => 'T-101', 'floor' => 'Ground Floor', 'capacity' => 4,
        ])->assertRedirect();

        $room = Room::where('room_number', 'T-101')->firstOrFail();

        $this->put(route('admin.rooms.update', $room), [
            'room_number' => 'T-101', 'capacity' => 2, 'status' => 'maintenance',
        ])->assertRedirect();

        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'capacity' => 2, 'status' => 'maintenance']);

        $this->delete(route('admin.rooms.destroy', $room))->assertRedirect();

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_admin_can_approve_application_and_allocation_lifecycle(): void
    {
        $this->actingAs($this->admin(), 'admin');

        $student = User::factory()->create(['gender' => 'Male']);
        $hostel = Hostel::create(['name' => 'Male Hostel - Test', 'category' => 'Male', 'status' => 'Active']);
        $room = Room::create(['hostel_id' => $hostel->id, 'room_number' => 'T-101', 'floor' => 'Ground Floor', 'capacity' => 2]);
        $application = HostelApplication::create([
            'user_id' => $student->id, 'hostel_id' => $hostel->id, 'reason' => 'Live far',
            'status' => 'pending', 'session' => config('sumas.session'),
        ]);

        // Reject flow.
        $this->post(route('admin.applications.reject', $application), [
            'rejection_reason' => 'Duplicate application',
        ])->assertRedirect();

        $this->assertDatabaseHas('hostel_applications', ['id' => $application->id, 'status' => 'rejected']);

        // Approve flow (re-submit as pending first).
        $application->update(['status' => 'pending', 'rejection_reason' => null]);

        $this->post(route('admin.applications.approve', $application))->assertRedirect();

        $this->assertDatabaseHas('hostel_applications', ['id' => $application->id, 'status' => 'approved']);
        $this->assertDatabaseHas('system_notifications', ['user_id' => $student->id, 'title' => 'Application Approved!']);

        // Allocate flow.
        $this->post(route('admin.allocation.store'), [
            'hostel_application_id' => $application->id,
            'room_id' => $room->id,
        ])->assertRedirect();

        $allocation = Allocation::where('hostel_application_id', $application->id)->firstOrFail();
        $this->assertSame('active', $allocation->status);
        $this->assertSame($room->id, $allocation->room_id);
        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'status' => 'partial']);

        // Available rooms endpoint still lists the room (1 of 2 beds open).
        $this->getJson(route('admin.allocation.rooms').'?hostel_id='.$hostel->id)
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['label' => 'Room T-101 (1 open)']);

        // Vacate flow.
        $this->post(route('admin.allocation.vacate', $allocation))->assertRedirect();

        $this->assertDatabaseHas('allocations', ['id' => $allocation->id, 'status' => 'vacated']);
        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'status' => 'vacant']);
    }
}
