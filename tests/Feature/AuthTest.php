<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_log_in_with_matric_number(): void
    {
        $student = User::factory()->create(['matric_number' => 'SUMAS/24/0001', 'password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'login' => 'SUMAS/24/0001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($student, 'web');
    }

    public function test_admin_can_log_in_with_email(): void
    {
        $admin = Admin::factory()->create(['email' => 'admin@sumas.edu.ng', 'password' => bcrypt('password')]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@sumas.edu.ng',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_guest_cannot_view_student_dashboard(): void
    {
        $this->get(route('student.dashboard'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_view_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }
}
