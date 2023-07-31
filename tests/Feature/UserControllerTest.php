<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user registration.
     */
    public function testUserRegistration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role' => 'Developer',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    public function testUserLogin()
    {
        $password = '123456@Aa'; // The password used during user registration

        // Create a user for testing
        $user = User::factory()->create([
            'password' => $password, // The password is bcrypt in User model with mutator
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token'
            ]);
    }

    public function testUserLogout()
    {
        // Create a user for testing
        $user = User::factory()->create();

        // Login the user (Assuming the login endpoint is working correctly)
        $token = $user->createToken('myapptoken')->plainTextToken;

        $headers = ['Authorization' => 'Bearer ' . $token];

        // Make a request to the logout endpoint
        $response = $this->postJson('/api/logout', [], $headers);

        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Logged out']);

        // Check if the user's token has been deleted from the database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }


}
