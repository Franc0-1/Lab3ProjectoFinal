<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PizzaRequest extends FormRequest
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
        $pizzaId = $this->route('pizza') ? $this->route('pizza')->id : null;
        
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('pizzas', 'name')
                    ->where('category_id', $this->input('category_id'))
                    ->ignore($pizzaId)
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'image' => [
                'nullable',
                'string',
                'max:255'
            ],
            'ingredients' => [
                'nullable',
                'array',
                'max:20'
            ],
            'ingredients.*' => [
                'string',
                'max:100'
            ],
            'available' => [
                'boolean'
            ],
            'featured' => [
                'boolean'
            ],
            'preparation_time' => [
                'required',
                'integer',
                'min:1',
                'max:120'
            ]
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'name.required' => 'El nombre de la pizza es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.unique' => 'Ya existe una pizza con este nombre en la categoría seleccionada.',
            'price.required' => 'El precio es obligatorio.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'price.max' => 'El precio no puede exceder $999.99.',
            'price.regex' => 'El precio debe tener máximo 2 decimales.',
            'description.max' => 'La descripción no puede exceder 1000 caracteres.',
            'ingredients.max' => 'No se pueden agregar más de 20 ingredientes.',
            'ingredients.*.max' => 'Cada ingrediente no puede exceder 100 caracteres.',
            'preparation_time.required' => 'El tiempo de preparación es obligatorio.',
            'preparation_time.min' => 'El tiempo de preparación debe ser al menos 1 minuto.',
            'preparation_time.max' => 'El tiempo de preparación no puede exceder 120 minutos.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'categoría',
            'name' => 'nombre',
            'description' => 'descripción',
            'price' => 'precio',
            'image' => 'imagen',
            'ingredients' => 'ingredientes',
            'available' => 'disponible',
            'featured' => 'destacada',
            'preparation_time' => 'tiempo de preparación'
        ];
    }
}
