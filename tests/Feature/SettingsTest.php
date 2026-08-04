<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_notification_settings_persist(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student, 'web');

        $this->put(route('student.settings.update'), [
            'email_notifications' => '1',
            'sms_alerts' => '0',
            'maintenance_alerts' => '1',
        ])->assertRedirect();

        $student->refresh();

        $this->assertTrue($student->settings['email_notifications']);
        $this->assertFalse($student->settings['sms_alerts']);
        $this->assertTrue($student->settings['maintenance_alerts']);
    }

    public function test_student_settings_page_remembers_saved_preferences(): void
    {
        $student = User::factory()->create(['settings' => [
            'email_notifications' => false,
            'sms_alerts' => true,
            'maintenance_alerts' => false,
        ]]);

        $this->actingAs($student, 'web')
            ->get(route('student.settings'))
            ->assertOk()
            ->assertSee('name="sms_alerts" value="1" checked', false);
    }

    public function test_admin_system_settings_persist(): void
    {
        $this->actingAs(Admin::factory()->create(), 'admin');

        $this->put(route('admin.settings.update'), [
            'institution_name' => 'Test University',
            'support_email' => 'support@test.edu',
            'academic_session' => '2030/2031',
        ])->assertRedirect();

        $this->assertSame('Test University', Setting::get('institution_name'));
        $this->assertSame('support@test.edu', Setting::get('support_email'));
        $this->assertSame('2030/2031', Setting::get('session'));
    }

    public function test_persisted_settings_override_sumas_config(): void
    {
        Setting::set('institution_name', 'Persisted Name');
        Setting::applyToConfig();

        $this->assertSame('Persisted Name', config('sumas.institution_name'));

        $this->actingAs(Admin::factory()->create(), 'admin')
            ->get(route('admin.settings'))
            ->assertOk()
            ->assertSee('value="Persisted Name"', false);
    }
}
