<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Discipline;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\CurriculumMatrix;
use App\Models\Enrollment;
use App\Models\ClassModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Teachers
        $teacher1 = Teacher::create([
            'name' => 'Prof. João Silva',
            'email' => 'joao.silva@instituicao.edu.br',
            'phone' => '(11) 98765-4321',
            'specialization' => 'Ciência da Computação',
            'employment_type' => 'full_time',
            'availability' => [
                'monday' => ['08:00-12:00', '14:00-18:00'],
                'wednesday' => ['08:00-12:00', '14:00-18:00'],
                'friday' => ['08:00-12:00']
            ],
            'preferences' => [
                'preferred_shifts' => ['morning', 'afternoon'],
                'max_classes_per_week' => 20
            ]
        ]);

        $teacher2 = Teacher::create([
            'name' => 'Profa. Maria Santos',
            'email' => 'maria.santos@instituicao.edu.br',
            'phone' => '(11) 98765-4322',
            'specialization' => 'Engenharia de Software',
            'employment_type' => 'full_time',
            'availability' => [
                'tuesday' => ['08:00-12:00', '14:00-18:00'],
                'thursday' => ['08:00-12:00', '14:00-18:00']
            ],
            'preferences' => [
                'preferred_shifts' => ['morning'],
                'max_classes_per_week' => 16
            ]
        ]);

        // Create Disciplines
        $disc1 = Discipline::create([
            'code' => 'ADS101',
            'name' => 'Algoritmos e Programação I',
            'description' => 'Introdução à lógica de programação e algoritmos',
            'workload_hours' => 80,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory'
        ]);

        $disc2 = Discipline::create([
            'code' => 'ADS102',
            'name' => 'Algoritmos e Programação II',
            'description' => 'Estruturas de dados e algoritmos avançados',
            'workload_hours' => 80,
            'weekly_hours' => 4,
            'credits' => 4,
            'prerequisites' => [$disc1->id],
            'type' => 'mandatory'
        ]);

        $disc3 = Discipline::create([
            'code' => 'ADS201',
            'name' => 'Banco de Dados',
            'description' => 'Modelagem e implementação de bancos de dados',
            'workload_hours' => 80,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'mandatory'
        ]);

        $disc4 = Discipline::create([
            'code' => 'ADS202',
            'name' => 'Desenvolvimento Web',
            'description' => 'Tecnologias e frameworks para desenvolvimento web',
            'workload_hours' => 80,
            'weekly_hours' => 4,
            'credits' => 4,
            'type' => 'elective'
        ]);

        // Create Classrooms
        $classroom1 = Classroom::create([
            'code' => 'LAB-A101',
            'building' => 'Bloco A',
            'floor' => '1º Andar',
            'capacity' => 40,
            'type' => 'lab',
            'resources' => [
                'computers' => 40,
                'projector' => true,
                'whiteboard' => true,
                'air_conditioning' => true
            ],
            'has_accessibility' => true
        ]);

        $classroom2 = Classroom::create([
            'code' => 'SALA-B201',
            'building' => 'Bloco B',
            'floor' => '2º Andar',
            'capacity' => 50,
            'type' => 'lecture',
            'resources' => [
                'projector' => true,
                'whiteboard' => true,
                'air_conditioning' => true,
                'sound_system' => true
            ],
            'has_accessibility' => true
        ]);

        // Create Students
        $student1 = Student::create([
            'registration_number' => '2025001',
            'name' => 'Carlos Alberto Souza',
            'email' => 'carlos.souza@aluno.edu.br',
            'phone' => '(11) 91234-5678',
            'birth_date' => '2003-05-15',
            'enrollment_date' => '2025-01-15',
            'status' => 'active',
            'gpa' => 8.50
        ]);

        $student2 = Student::create([
            'registration_number' => '2025002',
            'name' => 'Ana Paula Lima',
            'email' => 'ana.lima@aluno.edu.br',
            'phone' => '(11) 91234-5679',
            'birth_date' => '2004-08-22',
            'enrollment_date' => '2025-01-15',
            'status' => 'active',
            'gpa' => 9.20
        ]);

        // Create Curriculum Matrix
        $matrix = CurriculumMatrix::create([
            'code' => 'ADS-2025-1',
            'name' => 'Análise e Desenvolvimento de Sistemas 2025/1',
            'year' => 2025,
            'semester' => 1,
            'description' => 'Matriz curricular do curso de ADS para o ano de 2025'
        ]);

        // Attach disciplines to curriculum matrix
        $matrix->disciplines()->attach($disc1->id, ['semester' => 1, 'period' => 1]);
        $matrix->disciplines()->attach($disc2->id, ['semester' => 2, 'period' => 1]);
        $matrix->disciplines()->attach($disc3->id, ['semester' => 3, 'period' => 1]);
        $matrix->disciplines()->attach($disc4->id, ['semester' => 3, 'period' => 2]);

        // Create Classes
        $class1 = ClassModel::create([
            'code' => 'ADS101-2025-1-A',
            'discipline_id' => $disc1->id,
            'teacher_id' => $teacher1->id,
            'classroom_id' => $classroom1->id,
            'year' => 2025,
            'semester' => 1,
            'shift' => 'morning',
            'max_students' => 40,
            'enrolled_students' => 2,
            'schedule' => [
                'monday' => ['08:00-10:00'],
                'wednesday' => ['08:00-10:00']
            ]
        ]);

        $class2 = ClassModel::create([
            'code' => 'ADS201-2025-1-A',
            'discipline_id' => $disc3->id,
            'teacher_id' => $teacher2->id,
            'classroom_id' => $classroom2->id,
            'year' => 2025,
            'semester' => 1,
            'shift' => 'morning',
            'max_students' => 50,
            'enrolled_students' => 1,
            'schedule' => [
                'tuesday' => ['10:00-12:00'],
                'thursday' => ['10:00-12:00']
            ]
        ]);

        // Create Enrollments
        Enrollment::create([
            'student_id' => $student1->id,
            'discipline_id' => $disc1->id,
            'year' => 2025,
            'semester' => 1,
            'status' => 'enrolled'
        ]);

        Enrollment::create([
            'student_id' => $student1->id,
            'discipline_id' => $disc3->id,
            'year' => 2025,
            'semester' => 1,
            'status' => 'enrolled'
        ]);

        Enrollment::create([
            'student_id' => $student2->id,
            'discipline_id' => $disc1->id,
            'year' => 2025,
            'semester' => 1,
            'status' => 'enrolled'
        ]);
    }
}
