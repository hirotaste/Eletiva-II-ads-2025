<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\CurriculumMatrix;
use App\Models\Discipline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurriculumMatrixControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active curriculum matrices.
     */
    public function test_can_list_active_curriculum_matrices(): void
    {
        CurriculumMatrix::create([
            'code' => 'CM2024-1',
            'name' => 'Ciência da Computação 2024',
            'year' => 2024,
            'semester' => 1,
            'is_active' => true,
        ]);

        CurriculumMatrix::create([
            'code' => 'CM2023-2',
            'name' => 'Ciência da Computação 2023',
            'year' => 2023,
            'semester' => 2,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/curriculum-matrices');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a new curriculum matrix.
     */
    public function test_can_create_curriculum_matrix(): void
    {
        $matrixData = [
            'code' => 'CM2024-2',
            'name' => 'Engenharia de Software 2024',
            'year' => 2024,
            'semester' => 2,
            'description' => 'Matriz curricular do curso de Engenharia de Software',
        ];

        $response = $this->postJson('/api/curriculum-matrices', $matrixData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['code' => 'CM2024-2'])
                 ->assertJsonFragment(['name' => 'Engenharia de Software 2024']);

        $this->assertDatabaseHas('curriculum_matrix', [
            'code' => 'CM2024-2',
            'name' => 'Engenharia de Software 2024',
        ]);
    }

    /**
     * Test validation for required fields.
     */
    public function test_cannot_create_curriculum_matrix_without_required_fields(): void
    {
        $response = $this->postJson('/api/curriculum-matrices', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code', 'name', 'year', 'semester']);
    }

    /**
     * Test validation for unique code.
     */
    public function test_cannot_create_curriculum_matrix_with_duplicate_code(): void
    {
        CurriculumMatrix::create([
            'code' => 'CM2024-3',
            'name' => 'Sistemas de Informação 2024',
            'year' => 2024,
            'semester' => 1,
        ]);

        $response = $this->postJson('/api/curriculum-matrices', [
            'code' => 'CM2024-3',
            'name' => 'Sistemas de Informação 2024 - Nova',
            'year' => 2024,
            'semester' => 2,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code']);
    }

    /**
     * Test showing a specific curriculum matrix.
     */
    public function test_can_show_curriculum_matrix(): void
    {
        $matrix = CurriculumMatrix::create([
            'code' => 'CM2024-4',
            'name' => 'Análise e Desenvolvimento de Sistemas 2024',
            'year' => 2024,
            'semester' => 1,
        ]);

        $response = $this->getJson("/api/curriculum-matrices/{$matrix->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['code' => 'CM2024-4'])
                 ->assertJsonFragment(['name' => 'Análise e Desenvolvimento de Sistemas 2024']);
    }

    /**
     * Test updating a curriculum matrix.
     */
    public function test_can_update_curriculum_matrix(): void
    {
        $matrix = CurriculumMatrix::create([
            'code' => 'CM2024-5',
            'name' => 'Redes de Computadores 2024',
            'year' => 2024,
            'semester' => 1,
        ]);

        $updateData = [
            'name' => 'Redes de Computadores 2024 - Atualizado',
            'description' => 'Matriz atualizada com novas disciplinas',
        ];

        $response = $this->putJson("/api/curriculum-matrices/{$matrix->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Redes de Computadores 2024 - Atualizado']);

        $this->assertDatabaseHas('curriculum_matrix', [
            'id' => $matrix->id,
            'name' => 'Redes de Computadores 2024 - Atualizado',
        ]);
    }

    /**
     * Test deactivating a curriculum matrix.
     */
    public function test_can_deactivate_curriculum_matrix(): void
    {
        $matrix = CurriculumMatrix::create([
            'code' => 'CM2024-6',
            'name' => 'Segurança da Informação 2024',
            'year' => 2024,
            'semester' => 1,
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/curriculum-matrices/{$matrix->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Curriculum matrix deactivated successfully']);

        $this->assertDatabaseHas('curriculum_matrix', [
            'id' => $matrix->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test attaching a discipline to curriculum matrix.
     */
    public function test_can_attach_discipline_to_curriculum_matrix(): void
    {
        $matrix = CurriculumMatrix::create([
            'code' => 'CM2024-7',
            'name' => 'Test Matrix',
            'year' => 2024,
            'semester' => 1,
        ]);

        $discipline = Discipline::create([
            'name' => 'Test Discipline',
            'code' => 'TEST101',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'workload' => 60,
            'type' => 'mandatory',
        ]);

        $attachData = [
            'discipline_id' => $discipline->id,
            'semester' => 1,
            'period' => 1,
        ];

        $response = $this->postJson("/api/curriculum-matrices/{$matrix->id}/disciplines", $attachData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Discipline attached successfully']);

        $this->assertDatabaseHas('curriculum_disciplines', [
            'curriculum_matrix_id' => $matrix->id,
            'discipline_id' => $discipline->id,
        ]);
    }

    /**
     * Test validation when attaching discipline with invalid data.
     */
    public function test_cannot_attach_discipline_without_required_fields(): void
    {
        $matrix = CurriculumMatrix::create([
            'code' => 'CM2024-8',
            'name' => 'Test Matrix',
            'year' => 2024,
            'semester' => 1,
        ]);

        $response = $this->postJson("/api/curriculum-matrices/{$matrix->id}/disciplines", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['discipline_id', 'semester', 'period']);
    }

    /**
     * Test detaching a discipline from curriculum matrix.
     */
    public function test_can_detach_discipline_from_curriculum_matrix(): void
    {
        $matrix = CurriculumMatrix::create([
            'code' => 'CM2024-9',
            'name' => 'Test Matrix',
            'year' => 2024,
            'semester' => 1,
        ]);

        $discipline = Discipline::create([
            'name' => 'Test Discipline',
            'code' => 'TEST102',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'workload' => 60,
            'type' => 'mandatory',
        ]);

        // Attach first
        $matrix->disciplines()->attach($discipline->id, [
            'semester' => 1,
            'period' => 1,
        ]);

        // Then detach
        $response = $this->deleteJson("/api/curriculum-matrices/{$matrix->id}/disciplines/{$discipline->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Discipline detached successfully']);

        $this->assertDatabaseMissing('curriculum_disciplines', [
            'curriculum_matrix_id' => $matrix->id,
            'discipline_id' => $discipline->id,
        ]);
    }

    /**
     * Test validation for semester range.
     */
    public function test_cannot_create_curriculum_matrix_with_invalid_semester(): void
    {
        $response = $this->postJson('/api/curriculum-matrices', [
            'code' => 'CM2024-10',
            'name' => 'Test Matrix',
            'year' => 2024,
            'semester' => 3,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['semester']);
    }
}
