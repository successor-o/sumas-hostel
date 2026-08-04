<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_stores_message(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'John Doe', 'matric_number' => 'SUMAS/25/0001',
            'email' => 'john@example.com', 'subject' => 'Hostel enquiry', 'message' => 'Is accommodation still open?',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
            'subject' => 'Hostel enquiry',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $this->post(route('contact.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_admin_can_view_mark_read_and_delete_messages(): void
    {
        $message = ContactMessage::create([
            'name' => 'Jane Doe', 'email' => 'jane@example.com', 'subject' => 'Thanks', 'message' => 'All good!',
        ]);

        $this->actingAs(Admin::factory()->create(), 'admin');

        $this->get(route('admin.messages'))->assertOk()->assertSee('Jane Doe')->assertSee('Unread');

        $this->post(route('admin.messages.read', $message))->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'is_read' => true]);

        $this->post(route('admin.messages.unread', $message))->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'is_read' => false]);

        $this->delete(route('admin.messages.destroy', $message))->assertRedirect();
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    public function test_messages_page_can_filter_unread(): void
    {
        ContactMessage::create(['name' => 'One', 'email' => 'one@example.com', 'subject' => 'S1', 'message' => 'M1']);
        ContactMessage::create(['name' => 'Two', 'email' => 'two@example.com', 'subject' => 'S2', 'message' => 'M2', 'is_read' => true]);

        $this->actingAs(Admin::factory()->create(), 'admin');

        $response = $this->get(route('admin.messages', ['status' => 'unread']))->assertOk();
        $response->assertSee('One')->assertDontSee('Two');
    }
}
