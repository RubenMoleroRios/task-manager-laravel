<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_redirected_to_the_login_page_when_opening_tasks(): void
    {
        $response = $this->get('/tasks');

        $response->assertRedirect('/login');
    }

    public function test_a_user_can_register_with_a_unique_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ruben Garcia',
            'email' => 'RUBEN@EXAMPLE.COM',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'ruben@example.com',
            'name' => 'Ruben Garcia',
        ]);
    }

    public function test_registration_rejects_duplicate_emails_regardless_of_case(): void
    {
        User::factory()->create([
            'email' => 'ruben@example.com',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Ruben Garcia',
            'email' => 'RUBEN@EXAMPLE.COM',
            'password' => 'password123',
        ]);

        $response
            ->assertRedirect('/register')
            ->assertSessionHasErrors('email');
    }

    public function test_a_user_can_log_in_with_email_and_password(): void
    {
        $user = User::factory()->create([
            'email' => 'ruben@example.com',
        ]);

        $response = $this->post('/login', [
            'email' => 'RUBEN@EXAMPLE.COM',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertAuthenticatedAs($user);
    }
}
