<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $eventManager;
    private User $regularUser;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

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

    public function test_create_user_success_as_admin()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $this->actingAs($this->admin);

        $userData = [
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $this->admin->role_id,
            'full_name' => 'New User',
            'phone' => '1234567890',
            'address' => 'Some address',
            'gender' => 'male',
            'dob' => '2000-01-01',
        ];

        $response = $this->post(route('admin.user.submit'), $userData);

        $response->assertStatus(200);
        $response->assertJson(['res' => 'User create successfully!']);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);

        $this->assertDatabaseHas('user_details', [
            'user_id' => User::where('email', 'newuser@example.com')->first()->id,
            'full_name' => 'New User',
        ]);
    }

    public function test_update_user_success_as_admin()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $this->actingAs($this->admin);

        $user = User::create([
            'email' => 'user_to_update@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->regularUser->role_id,
        ]);

        $userDetail = UserDetail::create([
            'user_id' => $user->id,
            'full_name' => 'Old Name',
            'phone' => '0987654321',
            'address' => 'Old address',
            'gender' => 'female',
            'dob' => '1990-01-01',
        ]);

        $updatedData = [
            'email' => 'updated_user@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'role_id' => $this->eventManager->role_id,
            'full_name' => 'Updated Name',
            'phone' => '1234567890',
            'address' => 'New address',
            'gender' => 'male',
            'dob' => '1995-05-05',
        ];

        $response = $this->post(route('admin.user.update', $user->id), $updatedData);

        $response->assertStatus(200);
        $response->assertJson(['res' => 'User updated successfully!']);

        $this->assertDatabaseHas('users', [
            'email' => 'updated_user@example.com',
            'role_id' => $this->eventManager->role_id,
        ]);

        $this->assertDatabaseHas('user_details', [
            'user_id' => $user->id,
            'full_name' => 'Updated Name',
            'phone' => '1234567890',
            'address' => 'New address',
            'gender' => 'male',
            'dob' => '1995-05-05',
        ]);
    }

    public function test_delete_user_success_as_admin()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $this->actingAs($this->admin);

        $user = User::create([
            'email' => 'user_to_delete@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->regularUser->role_id,
        ]);

        $userDetail = UserDetail::create([
            'user_id' => $user->id,
            'full_name' => 'User to delete',
            'phone' => '1234567890',
            'address' => 'Some address',
            'gender' => 'male',
            'dob' => '1990-01-01',
        ]);

        $response = $this->post(route('admin.user.delete', $user->id));

        $response->assertStatus(200);
        $response->assertJson(['res' => 'User deleted successfully!']);

        $this->assertDatabaseMissing('users', [
            'email' => 'user_to_delete@example.com',
        ]);

        $this->assertDatabaseMissing('user_details', [
            'user_id' => $user->id,
        ]);
    }

    public function test_delete_user_fail_as_non_admin()
    {
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $this->actingAs($this->eventManager);

        $user = User::create([
            'email' => 'user_to_delete@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->regularUser->role_id,
        ]);

        $response = $this->post(route('admin.user.delete', $user->id));
        $response->assertStatus(403);
    }
}
