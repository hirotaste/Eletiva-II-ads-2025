<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating Enrollment data.
 * Centralizes validation rules and messages for enrollment operations.
 */
class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'semester' => 'required|integer|min:1|max:2',
            'status' => 'sometimes|in:enrolled,completed,failed,withdrawn',
            'grade' => 'nullable|numeric|min:0|max:10',
            'attendance_percentage' => 'nullable|integer|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'O aluno é obrigatório.',
            'student_id.exists' => 'O aluno informado não existe.',
            'discipline_id.required' => 'A disciplina é obrigatória.',
            'discipline_id.exists' => 'A disciplina informada não existe.',
            'year.required' => 'O ano é obrigatório.',
            'year.min' => 'O ano deve ser no mínimo 2020.',
            'semester.required' => 'O semestre é obrigatório.',
            'semester.min' => 'O semestre deve ser 1 ou 2.',
            'semester.max' => 'O semestre deve ser 1 ou 2.',
            'status.in' => 'O status deve ser enrolled, completed, failed ou withdrawn.',
            'grade.numeric' => 'A nota deve ser um número.',
            'grade.min' => 'A nota deve ser no mínimo 0.',
            'grade.max' => 'A nota não pode exceder 10.',
            'attendance_percentage.min' => 'A frequência deve ser no mínimo 0%.',
            'attendance_percentage.max' => 'A frequência não pode exceder 100%.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'aluno',
            'discipline_id' => 'disciplina',
            'year' => 'ano',
            'semester' => 'semestre',
            'status' => 'status',
            'grade' => 'nota',
            'attendance_percentage' => 'frequência',
        ];
    }
}
