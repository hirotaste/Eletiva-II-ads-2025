<?php

namespace Tests\Unit;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\Discipline;
use App\Repositories\DisciplineRepository;
use App\Services\DisciplineService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for DisciplineService.
 * Tests business logic and validation rules for discipline operations.
 */
class DisciplineServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DisciplineService $service;
    protected DisciplineRepository $repository;

    /**
     * Set up test environment before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DisciplineRepository();
        $this->service = new DisciplineService($this->repository);
    }

    /**
     * Test getting all active disciplines.
     *
     * @return void
     */
    public function test_can_get_all_active_disciplines(): void
    {
        Discipline::create([
            'code' => 'MATH101',
            'name' => 'Mathematics',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        Discipline::create([
            'code' => 'PHY101',
            'name' => 'Physics',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
            'is_active' => false,
        ]);

        $disciplines = $this->service->getAllActive();

        $this->assertCount(1, $disciplines);
        $this->assertEquals('MATH101', $disciplines->first()->code);
    }

    /**
     * Test getting a discipline by ID.
     *
     * @return void
     */
    public function test_can_get_discipline_by_id(): void
    {
        $discipline = Discipline::create([
            'code' => 'CS101',
            'name' => 'Computer Science',
            'workload_hours' => 80,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);

        $result = $this->service->getById($discipline->id);

        $this->assertEquals('CS101', $result->code);
        $this->assertEquals('Computer Science', $result->name);
    }

    /**
     * Test getting non-existent discipline throws exception.
     *
     * @return void
     */
    public function test_get_nonexistent_discipline_throws_exception(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->service->getById(999);
    }

    /**
     * Test creating a discipline.
     *
     * @return void
     */
    public function test_can_create_discipline(): void
    {
        $data = [
            'code' => 'BIO101',
            'name' => 'Biology',
            'workload_hours' => 60,
            'weekly_hours' => 3,
            'credits' => 3,
            'type' => 'mandatory',
        ];

        $discipline = $this->service->create($data);

        $this->assertInstanceOf(Discipline::class, $discipline);
        $this->assertEquals('BIO101', $discipline->code);
        $this->assertDatabaseHas('disciplines', ['code' => 'BIO101']);
    }

    /**
     * Test creating discipline with duplicate code throws exception.
     *
     * @return void
     */
    public function test_cannot_create_discipline_with_duplicate_code(): void
    {
        Discipline::create([
            'code' => 'DUP101',
            'name' => 'Duplicate',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create([
            'code' => 'DUP101',
            'name' => 'Another Discipline',
            'workload_hours' => 40,
            'weekly_hours' => 2,
            'credits' => 2,
            'type' => 'elective',
        ]);
    }

    /**
     * Test updating a discipline.
     *
     * @return void
     */
    public function test_can_update_discipline(): void
    {
        $discipline = Discipline::create([
            'code' => 'OLD101',
            'name' => 'Old Name',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);

        $updated = $this->service->update($discipline->id, [
            'name' => 'Updated Name',
            'workload_hours' => 80,
        ]);

        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals(80, $updated->workload_hours);
        $this->assertEquals('OLD101', $updated->code);
    }

    /**
     * Test updating discipline with duplicate code throws exception.
     *
     * @return void
     */
    public function test_cannot_update_discipline_with_duplicate_code(): void
    {
        Discipline::create([
            'code' => 'DISC1',
            'name' => 'Discipline 1',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);

        $discipline2 = Discipline::create([
            'code' => 'DISC2',
            'name' => 'Discipline 2',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->update($discipline2->id, [
            'code' => 'DISC1',
        ]);
    }

    /**
     * Test deactivating a discipline.
     *
     * @return void
     */
    public function test_can_deactivate_discipline(): void
    {
        $discipline = Discipline::create([
            'code' => 'ACT101',
            'name' => 'Active Discipline',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        $this->service->deactivate($discipline->id);

        $this->assertDatabaseHas('disciplines', [
            'id' => $discipline->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test creating discipline with valid prerequisites.
     *
     * @return void
     */
    public function test_can_create_discipline_with_prerequisites(): void
    {
        $prereq = Discipline::create([
            'code' => 'PRE101',
            'name' => 'Prerequisite',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        $data = [
            'code' => 'ADV101',
            'name' => 'Advanced',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
            'prerequisites' => [$prereq->id],
        ];

        $discipline = $this->service->create($data);

        $this->assertInstanceOf(Discipline::class, $discipline);
    }

    /**
     * Test calculating total workload.
     *
     * @return void
     */
    public function test_can_calculate_total_workload(): void
    {
        Discipline::create([
            'code' => 'CALC1',
            'name' => 'Calculus 1',
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);
        
        Discipline::create([
            'code' => 'CALC2',
            'name' => 'Calculus 2',
            'workload_hours' => 80,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory',
        ]);

        $disciplines = Discipline::all();
        $totalWorkload = $this->service->calculateTotalWorkload($disciplines);

        $this->assertEquals(140, $totalWorkload);
    }
}
