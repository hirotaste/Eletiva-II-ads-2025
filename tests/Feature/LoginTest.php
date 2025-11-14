<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if login page loads correctly
     */
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test if CSRF token is present in login form
     */
    public function test_login_form_has_csrf_token(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('_token');
    }

    /**
     * Test successful login with correct credentials
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        // Create a test user
        $user = User::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('password'),
            'level' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Attempt to login
        $response = $this->post('/login', [
            'email' => 'test@admin.com',
            'password' => 'password',
        ]);

        // Assert user is authenticated
        $this->assertAuthenticatedAs($user);
        
        // Assert redirect to dashboard
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test failed login with incorrect credentials
     */
    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        // Create a test user
        User::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('password'),
            'level' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Attempt to login with wrong password
        $response = $this->post('/login', [
            'email' => 'test@admin.com',
            'password' => 'wrong-password',
        ]);

        // Assert user is not authenticated
        $this->assertGuest();
        
        // Assert validation error
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test login validation
     */
    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }
}
