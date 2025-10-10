<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating Discipline data.
 * Centralizes validation rules and messages for discipline operations.
 */
class DisciplineRequest extends FormRequest
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
        $disciplineId = $this->route('discipline') ? $this->route('discipline')->id : null;

        return [
            'code' => 'required|string|max:20|unique:disciplines,code,' . $disciplineId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workload_hours' => 'required|integer|min:1|max:500',
            'weekly_hours' => 'required|integer|min:1|max:40',
            'credits' => 'required|integer|min:1|max:20',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:disciplines,id',
            'type' => 'required|in:mandatory,elective,optional',
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
            'code.required' => 'O código da disciplina é obrigatório.',
            'code.unique' => 'Este código de disciplina já está cadastrado.',
            'name.required' => 'O nome da disciplina é obrigatório.',
            'workload_hours.required' => 'A carga horária total é obrigatória.',
            'workload_hours.min' => 'A carga horária deve ser no mínimo 1 hora.',
            'workload_hours.max' => 'A carga horária não pode exceder 500 horas.',
            'weekly_hours.required' => 'A carga horária semanal é obrigatória.',
            'weekly_hours.min' => 'A carga horária semanal deve ser no mínimo 1 hora.',
            'weekly_hours.max' => 'A carga horária semanal não pode exceder 40 horas.',
            'credits.required' => 'O número de créditos é obrigatório.',
            'credits.min' => 'O número de créditos deve ser no mínimo 1.',
            'type.required' => 'O tipo de disciplina é obrigatório.',
            'type.in' => 'O tipo de disciplina deve ser mandatory, elective ou optional.',
            'prerequisites.*.exists' => 'Um ou mais pré-requisitos informados não existem.',
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
            'name' => 'nome',
            'description' => 'descrição',
            'workload_hours' => 'carga horária total',
            'weekly_hours' => 'carga horária semanal',
            'credits' => 'créditos',
            'prerequisites' => 'pré-requisitos',
            'type' => 'tipo',
        ];
    }
}
