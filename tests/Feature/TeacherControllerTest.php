<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active teachers.
     */
    public function test_can_list_active_teachers(): void
    {
        // Create test teachers
        Teacher::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'specialization' => 'Matemática',
            'employment_type' => 'full_time',
            'is_active' => true,
        ]);

        Teacher::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'specialization' => 'Física',
            'employment_type' => 'part_time',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/teachers');

        $response->assertStatus(200)
                 ->assertJsonCount(1); // Only active teachers
    }

    /**
     * Test creating a new teacher.
     */
    public function test_can_create_teacher(): void
    {
        $teacherData = [
            'name' => 'Pedro Oliveira',
            'email' => 'pedro@example.com',
            'phone' => '11999999999',
            'specialization' => 'Química',
            'employment_type' => 'full_time',
            'availability' => ['monday' => ['08:00-12:00'], 'wednesday' => ['14:00-18:00']],
            'preferences' => ['disciplines' => ['Chemistry', 'Physics']],
        ];

        $response = $this->postJson('/api/teachers', $teacherData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Pedro Oliveira'])
                 ->assertJsonFragment(['email' => 'pedro@example.com']);

        $this->assertDatabaseHas('teachers', [
            'name' => 'Pedro Oliveira',
            'email' => 'pedro@example.com',
        ]);
    }

    /**
     * Test validation when creating teacher with invalid data.
     */
    public function test_cannot_create_teacher_without_required_fields(): void
    {
        $response = $this->postJson('/api/teachers', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'specialization', 'employment_type']);
    }

    /**
     * Test validation for unique email.
     */
    public function test_cannot_create_teacher_with_duplicate_email(): void
    {
        Teacher::create([
            'name' => 'Carlos Lima',
            'email' => 'carlos@example.com',
            'specialization' => 'História',
            'employment_type' => 'full_time',
        ]);

        $response = $this->postJson('/api/teachers', [
            'name' => 'Carlos Silva',
            'email' => 'carlos@example.com',
            'specialization' => 'Geografia',
            'employment_type' => 'part_time',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test showing a specific teacher.
     */
    public function test_can_show_teacher(): void
    {
        $teacher = Teacher::create([
            'name' => 'Ana Costa',
            'email' => 'ana@example.com',
            'specialization' => 'Biologia',
            'employment_type' => 'full_time',
        ]);

        $response = $this->getJson("/api/teachers/{$teacher->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Ana Costa'])
                 ->assertJsonFragment(['email' => 'ana@example.com']);
    }

    /**
     * Test updating a teacher.
     */
    public function test_can_update_teacher(): void
    {
        $teacher = Teacher::create([
            'name' => 'Roberto Alves',
            'email' => 'roberto@example.com',
            'specialization' => 'Matemática',
            'employment_type' => 'full_time',
        ]);

        $updateData = [
            'name' => 'Roberto Alves Junior',
            'specialization' => 'Matemática Avançada',
        ];

        $response = $this->putJson("/api/teachers/{$teacher->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Roberto Alves Junior']);

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'name' => 'Roberto Alves Junior',
            'specialization' => 'Matemática Avançada',
        ]);
    }

    /**
     * Test soft deleting (deactivating) a teacher.
     */
    public function test_can_deactivate_teacher(): void
    {
        $teacher = Teacher::create([
            'name' => 'Sandra Rocha',
            'email' => 'sandra@example.com',
            'specialization' => 'Português',
            'employment_type' => 'part_time',
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/teachers/{$teacher->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Teacher deactivated successfully']);

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test invalid employment type.
     */
    public function test_cannot_create_teacher_with_invalid_employment_type(): void
    {
        $response = $this->postJson('/api/teachers', [
            'name' => 'Paulo Souza',
            'email' => 'paulo@example.com',
            'specialization' => 'Educação Física',
            'employment_type' => 'invalid_type',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['employment_type']);
    }
}
