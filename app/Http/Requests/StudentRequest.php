<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating Student data.
 * Centralizes validation rules and messages for student operations.
 */
class StudentRequest extends FormRequest
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
        $studentId = $this->route('student') ? $this->route('student')->id : null;

        return [
            'registration_number' => 'required|string|max:50|unique:students,registration_number,' . $studentId,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,graduated,suspended',
            'gpa' => 'nullable|numeric|min:0|max:10',
            'is_active' => 'sometimes|boolean',
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
            'registration_number.required' => 'O número de matrícula é obrigatório.',
            'registration_number.unique' => 'Este número de matrícula já está cadastrado.',
            'name.required' => 'O nome do aluno é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser active, inactive, graduated ou suspended.',
            'gpa.numeric' => 'O GPA deve ser um número.',
            'gpa.min' => 'O GPA deve ser no mínimo 0.',
            'gpa.max' => 'O GPA não pode exceder 10.',
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
            'registration_number' => 'número de matrícula',
            'name' => 'nome',
            'email' => 'email',
            'phone' => 'telefone',
            'status' => 'status',
            'gpa' => 'GPA',
        ];
    }
}
