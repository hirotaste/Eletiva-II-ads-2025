<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all active students.
     */
    public function test_can_list_active_students(): void
    {
        Student::create([
            'name' => 'Lucas Ferreira',
            'registration_number' => 'ST2024001',
            'email' => 'lucas@example.com',
            'birth_date' => '2000-05-15',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
            'is_active' => true,
        ]);

        Student::create([
            'name' => 'Camila Souza',
            'registration_number' => 'ST2024002',
            'email' => 'camila@example.com',
            'birth_date' => '1999-08-20',
            'enrollment_date' => '2024-01-15',
            'status' => 'inactive',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a new student.
     */
    public function test_can_create_student(): void
    {
        $studentData = [
            'name' => 'Rafael Costa',
            'registration_number' => 'ST2024003',
            'email' => 'rafael@example.com',
            'phone' => '11988888888',
            'birth_date' => '2001-03-10',
            'enrollment_date' => '2024-01-15',
            'address' => 'Rua A, 123',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/students', $studentData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Rafael Costa'])
                 ->assertJsonFragment(['registration_number' => 'ST2024003']);

        $this->assertDatabaseHas('students', [
            'name' => 'Rafael Costa',
            'registration_number' => 'ST2024003',
        ]);
    }

    /**
     * Test validation for required fields.
     */
    public function test_cannot_create_student_without_required_fields(): void
    {
        $response = $this->postJson('/api/students', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'registration_number', 'email', 'birth_date', 'enrollment_date']);
    }

    /**
     * Test validation for unique registration number.
     */
    public function test_cannot_create_student_with_duplicate_registration_number(): void
    {
        Student::create([
            'name' => 'Bruno Lima',
            'registration_number' => 'ST2024004',
            'email' => 'bruno@example.com',
            'birth_date' => '2000-12-05',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/students', [
            'name' => 'Bruno Silva',
            'registration_number' => 'ST2024004',
            'email' => 'bruno2@example.com',
            'birth_date' => '2001-01-10',
            'status' => 'active',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['registration_number']);
    }

    /**
     * Test validation for unique email.
     */
    public function test_cannot_create_student_with_duplicate_email(): void
    {
        Student::create([
            'name' => 'Julia Alves',
            'registration_number' => 'ST2024005',
            'email' => 'julia@example.com',
            'birth_date' => '2000-07-22',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/students', [
            'name' => 'Julia Santos',
            'registration_number' => 'ST2024006',
            'email' => 'julia@example.com',
            'birth_date' => '2001-02-18',
            'status' => 'active',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test showing a specific student.
     */
    public function test_can_show_student(): void
    {
        $student = Student::create([
            'name' => 'Mariana Rocha',
            'registration_number' => 'ST2024007',
            'email' => 'mariana@example.com',
            'birth_date' => '2000-11-30',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ]);

        $response = $this->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Mariana Rocha'])
                 ->assertJsonFragment(['registration_number' => 'ST2024007']);
    }

    /**
     * Test updating a student.
     */
    public function test_can_update_student(): void
    {
        $student = Student::create([
            'name' => 'Felipe Oliveira',
            'registration_number' => 'ST2024008',
            'email' => 'felipe@example.com',
            'birth_date' => '2000-04-25',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
        ]);

        $updateData = [
            'phone' => '11977777777',
        ];

        $response = $this->putJson("/api/students/{$student->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'phone' => '11977777777',
        ]);
    }

    /**
     * Test deactivating a student.
     */
    public function test_can_deactivate_student(): void
    {
        $student = Student::create([
            'name' => 'Gabriela Martins',
            'registration_number' => 'ST2024009',
            'email' => 'gabriela@example.com',
            'birth_date' => '2000-09-14',
            'enrollment_date' => '2024-01-15',
            'status' => 'active',
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Student deactivated successfully']);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test invalid student status.
     */
    public function test_cannot_create_student_with_invalid_status(): void
    {
        $response = $this->postJson('/api/students', [
            'name' => 'Test Student',
            'registration_number' => 'ST2024010',
            'email' => 'test@example.com',
            'birth_date' => '2000-01-01',
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }
}
