<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Role;
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
    private User $regularUser;


    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['role_name' => 'admin']);
        $eventManagerRole = Role::create(['role_name' => 'event_manager']);
        $userRole = Role::create(['role_name' => 'user']);


        $this->admin = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $this->regularUser = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        $this->event = Event::create([
            'name' => 'Sample Event',
            'description' => 'This is a sample event.',
            'location' => 'Sample Location',
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'approved',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_create_ticket_success_as_admin()
    {
        $this->actingAs($this->admin);

        $ticketData = [
            'event_id' => $this->event->id,
            'ticket_type' => 'vip',
            'price' => 100,
        ];

        $response = $this->post(route('admin.ticket.submit'), $ticketData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Ticket created successfully!',
        ]);

        $this->assertDatabaseHas('tickets', [
            'event_id' => $this->event->id,
            'ticket_type' => 'vip',
            'price' => 100,
        ]);
    }

    public function test_update_ticket_success_as_admin()
    {
        $this->actingAs($this->admin);

        $ticket = Ticket::create([
            'event_id' => $this->event->id,
            'ticket_type' => 'regular',
            'price' => 50,
        ]);

        $updatedData = [
            'ticket_type' => 'vip',
            'price' => 100,
        ];

        $response = $this->post(route('admin.ticket.update', $ticket->id), $updatedData);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Event created successfully!',
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'ticket_type' => 'VIP',
            'price' => 100,
        ]);
    }

    public function test_delete_ticket_success_as_admin()
    {
        $this->actingAs($this->admin);

        $ticket = Ticket::create([
            'event_id' => $this->event->id,
            'ticket_type' => 'vip',
            'price' => 50,
        ]);

        $response = $this->post(route('admin.ticket.delete', $ticket->id));

        $response->assertStatus(200);
        $response->assertJson([
            'res' => 'Ticket deleted successfully!',
        ]);

        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);
    }

    public function test_delete_ticket_fail_as_non_admin()
    {

        $this->actingAs($this->regularUser);

        $ticket = Ticket::create([
            'event_id' => $this->event->id,
            'ticket_type' => 'vip',
            'price' => 50,
        ]);

        $response = $this->post(route('admin.ticket.delete', $ticket->id));

        $response->assertStatus(403);
    }
}
