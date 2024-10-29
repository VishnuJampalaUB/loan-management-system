<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful user registration.
     */
    public function test_register_success()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully.',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    /**
     * Test registration with duplicate email.
     */
    public function test_register_failure_duplicate_email()
    {
        User::factory()->create(['email' => 'testuser@example.com']);

        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    /**
     * Test successful login.
     */
    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $data = [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['access_token', 'token_type']
            ]);
    }

    /**
     * Test login with incorrect credentials.
     */
    public function test_login_failure_incorrect_credentials()
    {
        $data = [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED)
            ->assertJson([
                'success' => false,
                'message' => 'Login failed',
            ]);
    }

    /**
     * Test successful logout.
     */
    public function test_logout_success()
    {
        // Create a user in the test database
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Attempt to log in and obtain a token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'access_token',
                    'token_type',
                ],
            ]);

        // Retrieve the access token from the login response
        $token = $loginResponse->json('data.access_token');

        // Act as the user with the retrieved token for subsequent requests
        $this->withHeader('Authorization', 'Bearer ' . $token);

        // Test logout functionality
        $logoutResponse = $this->postJson('/api/logout');

        $logoutResponse->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Successfully logged out.',
            ]);
    }

}
