<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->event = Event::create([
            'name' => 'Sample Event',
            'description' => 'This is a sample event.',
            'location' => 'Sample Location',
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'active',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_create_ticket_success_as_admin()
    {
        $this->actingAs($this->admin);

        $ticketData = [
            'event_id' => $this->event->id,
            'ticket_type' => 'VIP',
            'price' => 100,
        ];

        $response = $this->post(route('admin.ticket.create'), $ticketData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Ticket created successfully!',
        ]);

        // Kiểm tra dữ liệu có được lưu trong cơ sở dữ liệu không
        $this->assertDatabaseHas('tickets', [
            'event_id' => $this->event->id,
            'ticket_type' => 'VIP',
            'price' => 100,
        ]);
    }

    public function test_create_ticket_fail_without_event()
    {
        $this->actingAs($this->admin);

        // Dữ liệu vé không hợp lệ (không có event_id)
        $ticketData = [
            'ticket_type' => 'VIP',
            'price' => 100,
        ];

        // Gửi yêu cầu POST tạo vé
        $response = $this->post(route('admin.ticket.create'), $ticketData);

        // Kiểm tra phản hồi lỗi
        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Ticket creation failed! Please try again.'
        ]);
    }

    public function test_update_ticket_success_as_admin()
    {
        $this->actingAs($this->admin);

        // Tạo vé
        $ticket = Ticket::create([
            'event_id' => $this->event->id,
            'ticket_type' => 'General',
            'price' => 50,
        ]);

        // Dữ liệu cập nhật vé
        $updatedData = [
            'ticket_type' => 'VIP',
            'price' => 100,
        ];

        // Gửi yêu cầu POST cập nhật vé
        $response = $this->post(route('admin.ticket.update', $ticket->id), $updatedData);

        // Kiểm tra phản hồi
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Event created successfully!',
        ]);

        // Kiểm tra dữ liệu đã được cập nhật trong cơ sở dữ liệu
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'ticket_type' => 'VIP',
            'price' => 100,
        ]);
    }

    public function test_delete_ticket_success_as_admin()
    {
        $this->actingAs($this->admin);

        // Tạo vé
        $ticket = Ticket::create([
            'event_id' => $this->event->id,
            'ticket_type' => 'General',
            'price' => 50,
        ]);

        // Gửi yêu cầu xóa vé
        $response = $this->post(route('admin.ticket.delete', $ticket->id));

        // Kiểm tra phản hồi
        $response->assertStatus(200);
        $response->assertJson([
            'res' => 'Ticket deleted successfully!',
        ]);

        // Kiểm tra dữ liệu vé đã bị xóa khỏi cơ sở dữ liệu
        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);
    }

    public function test_delete_ticket_fail_as_non_admin()
    {
        $nonAdmin = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($nonAdmin);

        // Tạo vé
        $ticket = Ticket::create([
            'event_id' => $this->event->id,
            'ticket_type' => 'General',
            'price' => 50,
        ]);

        // Gửi yêu cầu xóa vé
        $response = $this->post(route('admin.ticket.delete', $ticket->id));

        // Kiểm tra phản hồi lỗi
        $response->assertStatus(403);
    }
}
