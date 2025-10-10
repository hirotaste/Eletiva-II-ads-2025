<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating Teacher data.
 * Centralizes validation rules and messages for teacher operations.
 */
class TeacherRequest extends FormRequest
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
        $teacherId = $this->route('teacher') ? $this->route('teacher')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacherId,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,contractor',
            'availability' => 'nullable|array',
            'preferences' => 'nullable|array',
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
            'name.required' => 'O nome do professor é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'specialization.required' => 'A especialização é obrigatória.',
            'employment_type.required' => 'O tipo de contratação é obrigatório.',
            'employment_type.in' => 'O tipo de contratação deve ser full_time, part_time ou contractor.',
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
            'name' => 'nome',
            'email' => 'email',
            'phone' => 'telefone',
            'specialization' => 'especialização',
            'employment_type' => 'tipo de contratação',
            'availability' => 'disponibilidade',
            'preferences' => 'preferências',
        ];
    }
}
