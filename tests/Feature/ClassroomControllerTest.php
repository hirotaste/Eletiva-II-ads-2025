<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active classrooms.
     */
    public function test_can_list_active_classrooms(): void
    {
        Classroom::create([
            'code' => 'A101',
            'building' => 'Bloco A',
            'floor' => '1º andar',
            'type' => 'lecture',
            'capacity' => 40,
            'is_active' => true,
        ]);

        Classroom::create([
            'code' => 'A102',
            'building' => 'Bloco A',
            'floor' => '1º andar',
            'type' => 'lecture',
            'capacity' => 40,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/classrooms');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a new classroom.
     */
    public function test_can_create_classroom(): void
    {
        $classroomData = [
            'code' => 'LAB101',
            'building' => 'Bloco B',
            'floor' => '2º andar',
            'type' => 'lab',
            'capacity' => 30,
            'has_accessibility' => true,
            'resources' => ['projector', 'computers', 'whiteboard'],
        ];

        $response = $this->postJson('/api/classrooms', $classroomData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['building' => 'Bloco B'])
                 ->assertJsonFragment(['code' => 'LAB101']);

        $this->assertDatabaseHas('classrooms', [
            'building' => 'Bloco B',
            'code' => 'LAB101',
        ]);
    }

    /**
     * Test validation for required fields.
     */
    public function test_cannot_create_classroom_without_required_fields(): void
    {
        $response = $this->postJson('/api/classrooms', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code', 'building', 'floor', 'type', 'capacity']);
    }

    /**
     * Test validation for unique code.
     */
    public function test_cannot_create_classroom_with_duplicate_code(): void
    {
        Classroom::create([
            'code' => 'B201',
            'building' => 'Bloco B',
            'floor' => '2º andar',
            'type' => 'lecture',
            'capacity' => 50,
        ]);

        $response = $this->postJson('/api/classrooms', [
            'code' => 'B201',
            'building' => 'Bloco B',
            'floor' => '2º andar',
            'type' => 'lecture',
            'capacity' => 50,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code']);
    }

    /**
     * Test showing a specific classroom.
     */
    public function test_can_show_classroom(): void
    {
        $classroom = Classroom::create([
            'code' => 'AUD001',
            'building' => 'Prédio Central',
            'floor' => 'Térreo',
            'type' => 'auditorium',
            'capacity' => 200,
        ]);

        $response = $this->getJson("/api/classrooms/{$classroom->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['building' => 'Prédio Central'])
                 ->assertJsonFragment(['code' => 'AUD001']);
    }

    /**
     * Test updating a classroom.
     */
    public function test_can_update_classroom(): void
    {
        $classroom = Classroom::create([
            'code' => 'SEM101',
            'building' => 'Bloco C',
            'floor' => '1º andar',
            'type' => 'seminar',
            'capacity' => 20,
        ]);

        $updateData = [
            'capacity' => 25,
            'resources' => ['projector', 'video_conference'],
        ];

        $response = $this->putJson("/api/classrooms/{$classroom->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('classrooms', [
            'id' => $classroom->id,
            'capacity' => 25,
        ]);
    }

    /**
     * Test deactivating a classroom.
     */
    public function test_can_deactivate_classroom(): void
    {
        $classroom = Classroom::create([
            'code' => 'C301',
            'building' => 'Bloco C',
            'floor' => '3º andar',
            'type' => 'lecture',
            'capacity' => 35,
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/classrooms/{$classroom->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Classroom deactivated successfully']);

        $this->assertDatabaseHas('classrooms', [
            'id' => $classroom->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test invalid classroom type.
     */
    public function test_cannot_create_classroom_with_invalid_type(): void
    {
        $response = $this->postJson('/api/classrooms', [
            'code' => 'TEST001',
            'building' => 'Test Building',
            'floor' => '1º andar',
            'type' => 'invalid_type',
            'capacity' => 30,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['type']);
    }

    /**
     * Test validation for capacity.
     */
    public function test_cannot_create_classroom_with_invalid_capacity(): void
    {
        $response = $this->postJson('/api/classrooms', [
            'code' => 'TEST002',
            'building' => 'Test Building',
            'floor' => '1º andar',
            'type' => 'lecture',
            'capacity' => -10,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['capacity']);
    }
}
