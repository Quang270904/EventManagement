<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
    public function test_new_users_can_register(): void
    {
        $role = Role::whereNotIn('role_name', ['admin', 'event_manager'])->first();

        if (!$role) {
            $role = Role::create([
                'role_name' => 'user',
            ]);
        }

        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'gender' => 'male',
            'dob' => '1990-01-01',
            'role_id' => $role->id,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.user'));
    }
}
