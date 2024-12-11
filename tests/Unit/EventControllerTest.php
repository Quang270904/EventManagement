<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $eventManager;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles for the test
        $adminRole = Role::create(['role_name' => 'admin']);
        $eventManagerRole = Role::create(['role_name' => 'event_manager']);
        $userRole = Role::create(['role_name' => 'user']);

        // Create users with different roles
        $this->admin = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $this->eventManager = User::create([
            'email' => 'event_manager@example.com',
            'password' => Hash::make('password'),
            'role_id' => $eventManagerRole->id,
        ]);

        $this->regularUser = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);
    }

    // Test: Create Event Success as Admin
    public function test_create_event_success_as_admin()
    {
        $this->actingAs($this->admin);

        $eventData = [
            'name' => 'New Event',
            'description' => 'Event description',
            'location' => 'Event location',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'pending',
        ];

        $response = $this->post(route('admin.event.submit'), $eventData);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success', 'message' => 'Event created successfully!']);

        $this->assertDatabaseHas('events', [
            'name' => 'New Event',
            'location' => 'Event location',
            'status' => 'pending',
        ]);
    }

    public function test_update_event_success_as_admin()
    {
        $this->actingAs($this->admin);

        $event = Event::create([
            'user_id' => $this->admin->id,
            'name' => 'Old Event',
            'description' => 'Old description',
            'location' => 'Old location',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'pending',
        ]);

        $updatedData = [
            'name' => 'Updated Event',
            'description' => 'Updated description',
            'location' => 'Updated location',
            'start_time' => now()->addDay()->addHours(1),
            'end_time' => now()->addDay()->addHours(3),
            'status' => 'approved',
        ];

        $response = $this->post(route('admin.event.update', $event->id), $updatedData);

        $response->assertStatus(200);
        $response->assertJson(['res' => 'Event updated successfully!']);

        $this->assertDatabaseHas('events', [
            'name' => 'Updated Event',
            'location' => 'Updated location',
            'status' => 'approved',
        ]);
    }

    public function test_delete_event_success_as_admin()
    {
        $this->actingAs($this->admin);

        $event = Event::create([
            'user_id' => $this->admin->id,
            'name' => 'Event to delete',
            'description' => 'Event description',
            'location' => 'Event location',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'approved',
        ]);

        $response = $this->post(route('admin.event.delete', $event->id));

        $response->assertStatus(200);
        $response->assertJson(['res' => 'Event deleted successfully!']);

        $this->assertDatabaseMissing('events', [
            'name' => 'Event to delete',
        ]);
    }

    public function test_delete_event_fail_as_non_admin()
    {
        $this->actingAs($this->eventManager);

        $event = Event::create([
            'user_id' => $this->eventManager->id,
            'name' => 'Event to delete',
            'description' => 'Event description',
            'location' => 'Event location',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'approved',
        ]);

        $response = $this->post(route('admin.event.delete', $event->id));

        $response->assertStatus(403);
    }
}
