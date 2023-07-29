<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

// Helper function to get the Bearer token for a given user's credentials
    protected function getBearerTokenForUser(array $credentials): string
    {
// Make a POST request to the login API endpoint with the user credentials
        $response = $this->postJson('/api/login', $credentials);

// Check if the token exists in the response
        if (!$response->json('token')) {
            throw new \Exception('Bearer token not found in the response.');
        }

// Return the Bearer token from the response
        return $response->json('token');
    }

// Test case to check if a task can be created
    public function testTaskCanBeCreated()
    {
// Create two users, one as the task creator and another as the assigned user
        $creatorUser = User::factory()->create();
        $assignedUser = User::factory()->create();

// Set the currently authenticated user as the creatorUser
        $this->actingAs($creatorUser);

// Task data to be sent in the request body
        $taskData = [
            'title' => 'Test Task',
            'status' => 'New',
            'priority' => 'High',
            'due_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'assigned_user_id' => $assignedUser->id,
        ];

// Get the Bearer token for the creatorUser
        $bearerToken = $this->getBearerTokenForUser([
            'email' => $creatorUser->email,
            'password' => '123456@Aa',
        ]);

// Make a POST request to create a new task
        $response = $this->postJson('/api/tasks', $taskData, [
            'Authorization' => 'Bearer ' . $bearerToken,
        ]);

// Assert that the request was successful (status code 201) and the task was added to the database
        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', $taskData);
    }

// Test case to check if a task can be updated
    public function testTaskCanBeUpdated()
    {
// Create two users, one as the task creator and another as the assigned user
        $creatorUser = User::factory()->create();
        $assignedUser = User::factory()->create();

// Set the currently authenticated user as the creatorUser
        $this->actingAs($creatorUser);

// Create a task with the specified creator and assigned user
        $task = Task::factory()->create(['creator_id' => $creatorUser->id, 'assigned_user_id' => $assignedUser->id]);

// Updated task data to be sent in the request body
        $updatedData = [
            'title' => 'Updated Task',
            'status' => 'In Progress',
            'priority' => 'Medium',
            'due_date' => now()->addDays(14)->format('Y-m-d H:i:s'),
            'assigned_user_id' => $assignedUser->id,
        ];

// Get the Bearer token for the creatorUser
        $bearerToken = $this->getBearerTokenForUser([
            'email' => $creatorUser->email,
            'password' => '123456@Aa',
        ]);

// Make a PUT request to update the task
        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData, [
            'Authorization' => 'Bearer ' . $bearerToken,
        ]);

// Assert that the request was successful (status code 200) and the task was updated in the database
        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', $updatedData);
    }

// Test case to check if a task can be deleted
    public function testTaskCanBeDeleted()
    {
// Create an admin user
        $adminUser = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($adminUser);

// Create a task
        $task = Task::factory()->create();

// Get the Bearer token for the admin user
        $bearerToken = $this->getBearerTokenForUser([
            'email' => $adminUser->email,
            'password' => '123456@Aa',
        ]);

// Make a DELETE request to delete the task
        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => 'Bearer ' . $bearerToken,
        ]);

// Assert that the request was successful (status code 200) and the task was deleted from the database
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

// Test case to check if a task cannot be deleted by a non-admin user
    public function testTaskCannotBeDeletedByNonAdminUser()
    {
// Create a non-admin user with the role of 'Developer'
        $user = User::factory()->create(['role' => 'Developer']);
        $this->actingAs($user);

// Create a task
        $task = Task::factory()->create();

// Get the Bearer token for the non-admin user
        $bearerToken = $this->getBearerTokenForUser([
            'email' => $user->email,
            'password' => '123456@Aa',
        ]);

// Make a DELETE request to delete the task
        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => 'Bearer ' . $bearerToken,
        ]);

// Assert that the request was forbidden (status code 403) since the user is not an admin
        $response->assertStatus(403);
    }
}
