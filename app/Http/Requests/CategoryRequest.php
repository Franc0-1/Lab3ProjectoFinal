<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        $categoryId = $this->route('category') ? $this->route('category')->id : null;
        
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
                Rule::unique('categories', 'name')->ignore($categoryId)
            ],
            'description' => [
                'nullable',
                'string',
                'max:500'
            ],
            'image' => [
                'nullable',
                'string',
                'max:255'
            ],
            'active' => [
                'boolean'
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:0',
                'max:9999'
            ]
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
            'image.max' => 'La URL de la imagen no puede exceder 255 caracteres.',
            'sort_order.required' => 'El orden de clasificación es obligatorio.',
            'sort_order.min' => 'El orden debe ser un número positivo.',
            'sort_order.max' => 'El orden no puede exceder 9999.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'image' => 'imagen',
            'active' => 'activa',
            'sort_order' => 'orden'
        ];
    }
}
