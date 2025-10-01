# API Documentation - Sistema de Gerenciamento Educacional

## Base URL
```
http://localhost:8000/api
```

## Autenticação
As rotas estão configuradas para funcionar sem autenticação para fins de desenvolvimento. Em produção, recomenda-se implementar Laravel Sanctum ou Passport.

---

## Teachers (Professores)

### List Teachers
```http
GET /api/teachers
```

**Response**
```json
[
  {
    "id": 1,
    "name": "Prof. João Silva",
    "email": "joao.silva@instituicao.edu.br",
    "phone": "(11) 98765-4321",
    "specialization": "Ciência da Computação",
    "employment_type": "full_time",
    "availability": {
      "monday": ["08:00-12:00", "14:00-18:00"],
      "wednesday": ["08:00-12:00"]
    },
    "preferences": {
      "preferred_shifts": ["morning", "afternoon"],
      "max_classes_per_week": 20
    },
    "is_active": true,
    "created_at": "2025-01-15T10:00:00.000000Z",
    "updated_at": "2025-01-15T10:00:00.000000Z"
  }
]
```

### Create Teacher
```http
POST /api/teachers
```

**Request Body**
```json
{
  "name": "Prof. Maria Santos",
  "email": "maria.santos@instituicao.edu.br",
  "phone": "(11) 98765-4322",
  "specialization": "Engenharia de Software",
  "employment_type": "full_time",
  "availability": {
    "tuesday": ["08:00-12:00"],
    "thursday": ["14:00-18:00"]
  },
  "preferences": {
    "preferred_shifts": ["morning"],
    "max_classes_per_week": 16
  }
}
```

**Response** (201 Created)
```json
{
  "id": 2,
  "name": "Prof. Maria Santos",
  // ... outros campos
}
```

### Get Teacher
```http
GET /api/teachers/{id}
```

**Response**
```json
{
  "id": 1,
  "name": "Prof. João Silva",
  // ... outros campos
  "classes": [
    {
      "id": 1,
      "code": "ADS101-2025-1-A",
      "discipline_id": 1,
      "year": 2025,
      "semester": 1
    }
  ]
}
```

### Update Teacher
```http
PUT /api/teachers/{id}
```

**Request Body**
```json
{
  "phone": "(11) 98765-9999",
  "availability": {
    "monday": ["08:00-12:00"],
    "friday": ["14:00-18:00"]
  }
}
```

### Delete Teacher (Soft)
```http
DELETE /api/teachers/{id}
```

**Response**
```json
{
  "message": "Teacher deactivated successfully"
}
```

---

## Disciplines (Disciplinas)

### List Disciplines
```http
GET /api/disciplines
```

### Create Discipline
```http
POST /api/disciplines
```

**Request Body**
```json
{
  "code": "ADS101",
  "name": "Algoritmos e Programação I",
  "description": "Introdução à lógica de programação",
  "workload_hours": 80,
  "weekly_hours": 4,
  "credits": 4,
  "type": "mandatory"
}
```

### Get Discipline
```http
GET /api/disciplines/{id}
```

### Update Discipline
```http
PUT /api/disciplines/{id}
```

**Request Body**
```json
{
  "name": "Algoritmos e Programação I - Atualizado",
  "workload_hours": 100,
  "prerequisites": [1, 2]
}
```

### Delete Discipline (Soft)
```http
DELETE /api/disciplines/{id}
```

---

## Students (Alunos)

### List Students
```http
GET /api/students
```

### Create Student
```http
POST /api/students
```

**Request Body**
```json
{
  "registration_number": "2025001",
  "name": "Carlos Alberto Souza",
  "email": "carlos.souza@aluno.edu.br",
  "phone": "(11) 91234-5678",
  "birth_date": "2003-05-15",
  "enrollment_date": "2025-01-15"
}
```

**Response** (201 Created)
```json
{
  "id": 1,
  "registration_number": "2025001",
  "name": "Carlos Alberto Souza",
  "email": "carlos.souza@aluno.edu.br",
  "phone": "(11) 91234-5678",
  "birth_date": "2003-05-15",
  "enrollment_date": "2025-01-15",
  "status": "active",
  "history": null,
  "gpa": 0.00,
  "created_at": "2025-01-15T10:00:00.000000Z",
  "updated_at": "2025-01-15T10:00:00.000000Z"
}
```

### Get Student
```http
GET /api/students/{id}
```

**Response**
```json
{
  "id": 1,
  "registration_number": "2025001",
  "name": "Carlos Alberto Souza",
  // ... outros campos
  "enrollments": [
    {
      "id": 1,
      "student_id": 1,
      "discipline_id": 1,
      "year": 2025,
      "semester": 1,
      "status": "enrolled",
      "discipline": {
        "id": 1,
        "code": "ADS101",
        "name": "Algoritmos e Programação I"
      }
    }
  ]
}
```

### Update Student
```http
PUT /api/students/{id}
```

**Request Body**
```json
{
  "phone": "(11) 91234-9999",
  "gpa": 8.50,
  "history": {
    "awards": ["Dean's List 2025"],
    "achievements": ["Best Project Award"]
  }
}
```

### Delete Student (Soft)
```http
DELETE /api/students/{id}
```

---

## Classrooms (Salas de Aula)

### List Classrooms
```http
GET /api/classrooms
```

### Create Classroom
```http
POST /api/classrooms
```

**Request Body**
```json
{
  "code": "LAB-A101",
  "building": "Bloco A",
  "floor": "1º Andar",
  "capacity": 40,
  "type": "lab",
  "resources": {
    "computers": 40,
    "projector": true,
    "whiteboard": true,
    "air_conditioning": true
  },
  "has_accessibility": true
}
```

### Get Classroom
```http
GET /api/classrooms/{id}
```

### Update Classroom
```http
PUT /api/classrooms/{id}
```

### Delete Classroom (Soft)
```http
DELETE /api/classrooms/{id}
```

---

## Curriculum Matrix (Matriz Curricular)

### List Curriculum Matrices
```http
GET /api/curriculum-matrices
```

### Create Curriculum Matrix
```http
POST /api/curriculum-matrices
```

**Request Body**
```json
{
  "code": "ADS-2025-1",
  "name": "Análise e Desenvolvimento de Sistemas 2025/1",
  "year": 2025,
  "semester": 1,
  "description": "Matriz curricular do curso de ADS"
}
```

### Get Curriculum Matrix
```http
GET /api/curriculum-matrices/{id}
```

**Response**
```json
{
  "id": 1,
  "code": "ADS-2025-1",
  "name": "Análise e Desenvolvimento de Sistemas 2025/1",
  "year": 2025,
  "semester": 1,
  "description": "Matriz curricular do curso de ADS",
  "is_active": true,
  "disciplines": [
    {
      "id": 1,
      "code": "ADS101",
      "name": "Algoritmos e Programação I",
      "pivot": {
        "semester": 1,
        "period": 1
      }
    }
  ]
}
```

### Update Curriculum Matrix
```http
PUT /api/curriculum-matrices/{id}
```

### Delete Curriculum Matrix (Soft)
```http
DELETE /api/curriculum-matrices/{id}
```

### Attach Discipline to Matrix
```http
POST /api/curriculum-matrices/{id}/disciplines
```

**Request Body**
```json
{
  "discipline_id": 1,
  "semester": 1,
  "period": 1
}
```

**Response**
```json
{
  "message": "Discipline attached successfully"
}
```

### Detach Discipline from Matrix
```http
DELETE /api/curriculum-matrices/{id}/disciplines/{disciplineId}
```

**Response**
```json
{
  "message": "Discipline detached successfully"
}
```

---

## Enrollments (Matrículas)

### List Enrollments
```http
GET /api/enrollments
```

**Response**
```json
[
  {
    "id": 1,
    "student_id": 1,
    "discipline_id": 1,
    "year": 2025,
    "semester": 1,
    "status": "enrolled",
    "grade": null,
    "attendance_percentage": null,
    "student": {
      "id": 1,
      "name": "Carlos Alberto Souza",
      "registration_number": "2025001"
    },
    "discipline": {
      "id": 1,
      "code": "ADS101",
      "name": "Algoritmos e Programação I"
    }
  }
]
```

### Create Enrollment
```http
POST /api/enrollments
```

**Request Body**
```json
{
  "student_id": 1,
  "discipline_id": 1,
  "year": 2025,
  "semester": 1
}
```

### Get Enrollment
```http
GET /api/enrollments/{id}
```

### Update Enrollment
```http
PUT /api/enrollments/{id}
```

**Request Body**
```json
{
  "status": "completed",
  "grade": 8.5,
  "attendance_percentage": 95
}
```

### Delete Enrollment (Withdraw)
```http
DELETE /api/enrollments/{id}
```

**Response**
```json
{
  "message": "Enrollment withdrawn successfully"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email has already been taken."
    ],
    "workload_hours": [
      "The workload hours must be at least 1."
    ]
  }
}
```

### Not Found (404)
```json
{
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "message": "Internal server error",
  "error": "Error details..."
}
```

---

## Data Types Reference

### Employment Types
- `full_time`
- `part_time`
- `contractor`

### Discipline Types
- `mandatory`
- `elective`
- `optional`

### Classroom Types
- `lecture`
- `lab`
- `auditorium`
- `seminar`

### Student Status
- `active`
- `inactive`
- `graduated`
- `suspended`

### Class Shifts
- `morning`
- `afternoon`
- `evening`
- `night`

### Enrollment Status
- `enrolled`
- `completed`
- `failed`
- `withdrawn`

---

## Notes

1. All dates should be in ISO 8601 format (YYYY-MM-DD)
2. All timestamps are in UTC
3. Pagination is not implemented yet but can be added using Laravel's built-in pagination
4. All JSON fields accept null values
5. Soft deletes are implemented by setting `is_active` to `false` or changing `status` field
