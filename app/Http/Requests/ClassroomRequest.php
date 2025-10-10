<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating Classroom data.
 * Centralizes validation rules and messages for classroom operations.
 */
class ClassroomRequest extends FormRequest
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
        $classroomId = $this->route('classroom') ? $this->route('classroom')->id : null;

        return [
            'code' => 'required|string|max:20|unique:classrooms,code,' . $classroomId,
            'building' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1|max:500',
            'type' => 'required|in:lecture,laboratory,auditorium,seminar',
            'has_accessibility' => 'sometimes|boolean',
            'equipment' => 'nullable|array',
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
            'code.required' => 'O código da sala é obrigatório.',
            'code.unique' => 'Este código de sala já está cadastrado.',
            'building.required' => 'O prédio é obrigatório.',
            'capacity.required' => 'A capacidade é obrigatória.',
            'capacity.min' => 'A capacidade deve ser no mínimo 1.',
            'capacity.max' => 'A capacidade não pode exceder 500.',
            'type.required' => 'O tipo de sala é obrigatório.',
            'type.in' => 'O tipo de sala deve ser lecture, laboratory, auditorium ou seminar.',
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
            'code' => 'código',
            'building' => 'prédio',
            'capacity' => 'capacidade',
            'type' => 'tipo',
            'has_accessibility' => 'acessibilidade',
            'equipment' => 'equipamentos',
        ];
    }
}
