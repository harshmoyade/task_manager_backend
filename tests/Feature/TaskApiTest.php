<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task_success(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'status' => 'pending',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Task']);
    }

    public function test_create_task_validation_failure(): void
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    public function test_list_tasks(): void
    {
        Task::factory()->count(3)->create();

        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->create();

        $this->putJson("/api/tasks/{$task->id}", [
            'status' => 'completed',
        ])->assertStatus(200)
            ->assertJsonFragment(['status' => 'completed']);
    }

    public function test_delete_task(): void
    {
        $task = Task::factory()->create();

        $this->deleteJson("/api/tasks/{$task->id}")
            ->assertStatus(204);
    }
}
