<?php

namespace Tests\Unit;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use App\Services\TeacherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for TeacherService.
 * Tests business logic and validation rules for teacher operations.
 */
class TeacherServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TeacherService $service;
    protected TeacherRepository $repository;

    /**
     * Set up test environment before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TeacherRepository();
        $this->service = new TeacherService($this->repository);
    }

    /**
     * Test getting all active teachers.
     *
     * @return void
     */
    public function test_can_get_all_active_teachers(): void
    {
        Teacher::create([
            'name' => 'Active Teacher',
            'email' => 'active@example.com',
            'specialization' => 'Math',
            'employment_type' => 'full_time',
            'is_active' => true,
        ]);

        Teacher::create([
            'name' => 'Inactive Teacher',
            'email' => 'inactive@example.com',
            'specialization' => 'Science',
            'employment_type' => 'part_time',
            'is_active' => false,
        ]);

        $teachers = $this->service->getAllActive();

        $this->assertCount(1, $teachers);
        $this->assertEquals('Active Teacher', $teachers->first()->name);
    }

    /**
     * Test getting a teacher by ID.
     *
     * @return void
     */
    public function test_can_get_teacher_by_id(): void
    {
        $teacher = Teacher::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'specialization' => 'Physics',
            'employment_type' => 'full_time',
        ]);

        $result = $this->service->getById($teacher->id);

        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
    }

    /**
     * Test getting non-existent teacher throws exception.
     *
     * @return void
     */
    public function test_get_nonexistent_teacher_throws_exception(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->service->getById(999);
    }

    /**
     * Test creating a teacher.
     *
     * @return void
     */
    public function test_can_create_teacher(): void
    {
        $data = [
            'name' => 'New Teacher',
            'email' => 'newteacher@example.com',
            'specialization' => 'Chemistry',
            'employment_type' => 'contractor',
        ];

        $teacher = $this->service->create($data);

        $this->assertInstanceOf(Teacher::class, $teacher);
        $this->assertEquals('New Teacher', $teacher->name);
        $this->assertDatabaseHas('teachers', ['email' => 'newteacher@example.com']);
    }

    /**
     * Test creating teacher with duplicate email throws exception.
     *
     * @return void
     */
    public function test_cannot_create_teacher_with_duplicate_email(): void
    {
        Teacher::create([
            'name' => 'Existing Teacher',
            'email' => 'duplicate@example.com',
            'specialization' => 'Math',
            'employment_type' => 'full_time',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create([
            'name' => 'Another Teacher',
            'email' => 'duplicate@example.com',
            'specialization' => 'Science',
            'employment_type' => 'part_time',
        ]);
    }

    /**
     * Test updating a teacher.
     *
     * @return void
     */
    public function test_can_update_teacher(): void
    {
        $teacher = Teacher::create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'specialization' => 'Biology',
            'employment_type' => 'full_time',
        ]);

        $updated = $this->service->update($teacher->id, [
            'name' => 'Updated Name',
            'specialization' => 'Advanced Biology',
        ]);

        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals('Advanced Biology', $updated->specialization);
        $this->assertEquals('original@example.com', $updated->email);
    }

    /**
     * Test updating teacher with duplicate email throws exception.
     *
     * @return void
     */
    public function test_cannot_update_teacher_with_duplicate_email(): void
    {
        Teacher::create([
            'name' => 'Teacher 1',
            'email' => 'teacher1@example.com',
            'specialization' => 'Math',
            'employment_type' => 'full_time',
        ]);

        $teacher2 = Teacher::create([
            'name' => 'Teacher 2',
            'email' => 'teacher2@example.com',
            'specialization' => 'Science',
            'employment_type' => 'part_time',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->update($teacher2->id, [
            'email' => 'teacher1@example.com',
        ]);
    }

    /**
     * Test deactivating a teacher.
     *
     * @return void
     */
    public function test_can_deactivate_teacher(): void
    {
        $teacher = Teacher::create([
            'name' => 'Active Teacher',
            'email' => 'active@example.com',
            'specialization' => 'History',
            'employment_type' => 'full_time',
            'is_active' => true,
        ]);

        $this->service->deactivate($teacher->id);

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test validating teacher availability.
     *
     * @return void
     */
    public function test_can_validate_availability(): void
    {
        $validAvailability = [
            'monday' => ['08:00-12:00', '14:00-18:00'],
            'wednesday' => ['08:00-12:00'],
        ];

        $result = $this->service->validateAvailability($validAvailability);

        $this->assertTrue($result);
    }

    /**
     * Test validating invalid availability format.
     *
     * @return void
     */
    public function test_invalid_availability_returns_false(): void
    {
        $invalidAvailability = [
            'invalidday' => ['08:00-12:00'],
        ];

        $result = $this->service->validateAvailability($invalidAvailability);

        $this->assertFalse($result);
    }
}
