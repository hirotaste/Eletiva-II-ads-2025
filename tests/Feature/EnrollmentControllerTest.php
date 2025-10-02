<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Discipline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $discipline;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test student and discipline
        $this->student = Student::create([
            'name' => 'Test Student',
            'registration_number' => 'ST2024001',
            'email' => 'student@example.com',
            'birth_date' => '2000-01-01',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ]);

        $this->discipline = Discipline::create([
            'name' => 'Test Discipline',
            'code' => 'TEST101',
            'credits' => 4,
            'workload_hours' => 60,
            'weekly_hours' => 4,
            'workload' => 60,
            'type' => 'mandatory',
        ]);
    }

    /**
     * Test listing all enrollments.
     */
    public function test_can_list_enrollments(): void
    {
        Enrollment::create([
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
            'status' => 'enrolled',
        ]);

        $response = $this->getJson('/api/enrollments');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a new enrollment.
     */
    public function test_can_create_enrollment(): void
    {
        $enrollmentData = [
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
        ];

        $response = $this->postJson('/api/enrollments', $enrollmentData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['status' => 'enrolled']);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
        ]);
    }

    /**
     * Test validation for required fields.
     */
    public function test_cannot_create_enrollment_without_required_fields(): void
    {
        $response = $this->postJson('/api/enrollments', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['student_id', 'discipline_id', 'year', 'semester']);
    }

    /**
     * Test validation for invalid student.
     */
    public function test_cannot_create_enrollment_with_invalid_student(): void
    {
        $response = $this->postJson('/api/enrollments', [
            'student_id' => 99999,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['student_id']);
    }

    /**
     * Test validation for invalid discipline.
     */
    public function test_cannot_create_enrollment_with_invalid_discipline(): void
    {
        $response = $this->postJson('/api/enrollments', [
            'student_id' => $this->student->id,
            'discipline_id' => 99999,
            'year' => 2024,
            'semester' => 1,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['discipline_id']);
    }

    /**
     * Test validation for semester.
     */
    public function test_cannot_create_enrollment_with_invalid_semester(): void
    {
        $response = $this->postJson('/api/enrollments', [
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 3,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['semester']);
    }

    /**
     * Test showing a specific enrollment.
     */
    public function test_can_show_enrollment(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
            'status' => 'enrolled',
        ]);

        $response = $this->getJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'enrolled']);
    }

    /**
     * Test updating an enrollment.
     */
    public function test_can_update_enrollment_status_and_grade(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
            'status' => 'enrolled',
        ]);

        $updateData = [
            'status' => 'completed',
            "grade" => "8.50",
            'attendance_percentage' => 95,
        ];

        $response = $this->putJson("/api/enrollments/{$enrollment->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'completed'])
                 ->assertJsonFragment(["grade" => "8.50"]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'completed',
            "grade" => "8.50",
            'attendance_percentage' => 95,
        ]);
    }

    /**
     * Test validation for grade range.
     */
    public function test_cannot_update_enrollment_with_invalid_grade(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
            'status' => 'enrolled',
        ]);

        $response = $this->putJson("/api/enrollments/{$enrollment->id}", [
            'grade' => 11,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['grade']);
    }

    /**
     * Test withdrawing an enrollment.
     */
    public function test_can_withdraw_enrollment(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
            'status' => 'enrolled',
        ]);

        $response = $this->deleteJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Enrollment withdrawn successfully']);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'withdrawn',
        ]);
    }

    /**
     * Test invalid status.
     */
    public function test_cannot_update_enrollment_with_invalid_status(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'discipline_id' => $this->discipline->id,
            'year' => 2024,
            'semester' => 1,
            'status' => 'enrolled',
        ]);

        $response = $this->putJson("/api/enrollments/{$enrollment->id}", [
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }
}
