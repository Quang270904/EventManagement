<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ], ['Accept' => 'application/json']);  

        $response->assertStatus(302); 

        Notification::assertSentTo(
            [$user], ResetPassword::class
        );
    }

    /** @test */
    public function reset_password_link_can_not_be_requested_with_invalid_email(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'invalid-email@example.com',
        ], ['Accept' => 'application/json']); 

        $response->assertStatus(302); 
        $response->assertSessionHasErrors('email');
    }
}
