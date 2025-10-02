<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Discipline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DisciplineControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active disciplines.
     */
    public function test_can_list_active_disciplines(): void
    {
        Discipline::create([
            'name' => 'Cálculo I',
            'code' => 'MAT101',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        Discipline::create([
            'name' => 'Cálculo II',
            'code' => 'MAT102',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/disciplines');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a new discipline.
     */
    public function test_can_create_discipline(): void
    {
        $disciplineData = [
            'name' => 'Algoritmos e Estruturas de Dados',
            'code' => 'CS201',
            'description' => 'Introdução a algoritmos fundamentais',
            'credits' => 6,
            'workload_hours' => 90,
            'weekly_hours' => 6,
            'type' => 'mandatory',
            'prerequisites' => [],
        ];

        $response = $this->postJson('/api/disciplines', $disciplineData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Algoritmos e Estruturas de Dados'])
                 ->assertJsonFragment(['code' => 'CS201']);

        $this->assertDatabaseHas('disciplines', [
            'name' => 'Algoritmos e Estruturas de Dados',
            'code' => 'CS201',
        ]);
    }

    /**
     * Test validation for required fields.
     */
    public function test_cannot_create_discipline_without_required_fields(): void
    {
        $response = $this->postJson('/api/disciplines', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'code', 'credits', 'workload_hours', 'weekly_hours', 'type']);
    }

    /**
     * Test validation for unique code.
     */
    public function test_cannot_create_discipline_with_duplicate_code(): void
    {
        Discipline::create([
            'name' => 'Banco de Dados',
            'code' => 'CS301',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
        ]);

        $response = $this->postJson('/api/disciplines', [
            'name' => 'Sistemas de Banco de Dados',
            'code' => 'CS301',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code']);
    }

    /**
     * Test showing a specific discipline.
     */
    public function test_can_show_discipline(): void
    {
        $discipline = Discipline::create([
            'name' => 'Programação Web',
            'code' => 'WEB101',
            'credits' => 5,
            'workload_hours' => 75,
            'weekly_hours' => 5,
            'type' => 'mandatory',
        ]);

        $response = $this->getJson("/api/disciplines/{$discipline->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Programação Web'])
                 ->assertJsonFragment(['code' => 'WEB101']);
    }

    /**
     * Test updating a discipline.
     */
    public function test_can_update_discipline(): void
    {
        $discipline = Discipline::create([
            'name' => 'Redes de Computadores',
            'code' => 'NET101',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
        ]);

        $updateData = [
            'name' => 'Redes de Computadores I',
            'workload_hours' => 75,
            'weekly_hours' => 5,
        ];

        $response = $this->putJson("/api/disciplines/{$discipline->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Redes de Computadores I']);

        $this->assertDatabaseHas('disciplines', [
            'id' => $discipline->id,
            'name' => 'Redes de Computadores I',
            'workload_hours' => 75,
            'weekly_hours' => 5,
        ]);
    }

    /**
     * Test deactivating a discipline.
     */
    public function test_can_deactivate_discipline(): void
    {
        $discipline = Discipline::create([
            'name' => 'Inteligência Artificial',
            'code' => 'AI101',
            'credits' => 6,
            'workload_hours' => 90,
            'weekly_hours' => 6,
            'type' => 'elective',
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/disciplines/{$discipline->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Discipline deactivated successfully']);

        $this->assertDatabaseHas('disciplines', [
            'id' => $discipline->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test invalid discipline type.
     */
    public function test_cannot_create_discipline_with_invalid_type(): void
    {
        $response = $this->postJson('/api/disciplines', [
            'name' => 'Test Discipline',
            'code' => 'TEST001',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'invalid_type',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['type']);
    }

    /**
     * Test creating discipline with prerequisites.
     */
    public function test_can_create_discipline_with_prerequisites(): void
    {
        $prereq = Discipline::create([
            'name' => 'Programação I',
            'code' => 'CS101',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
        ]);

        $disciplineData = [
            'name' => 'Programação II',
            'code' => 'CS102',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'type' => 'mandatory',
            'prerequisites' => [$prereq->id],
        ];

        $response = $this->postJson('/api/disciplines', $disciplineData);

        $response->assertStatus(201);
        
        $discipline = Discipline::where('code', 'CS102')->first();
        $this->assertEquals([$prereq->id], $discipline->prerequisites);
    }
}
