<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class OrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', Order::getStatuses()),
            'delivery_method' => 'required|in:' . implode(',', Order::getDeliveryMethods()),
            'payment_method' => 'required|in:' . implode(',', Order::getPaymentMethods()),
            'delivery_address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'delivery_date' => 'nullable|date|after:now',
            'items' => 'required|array|min:1',
            'items.*.pizza_id' => 'required|exists:pizzas,id',
            'items.*.quantity' => 'required|integer|min:1|max:10',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];

        // For updates, make some fields optional
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            $rules['items'] = 'sometimes|array|min:1';
        }

        return $rules;
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'customer',
            'total_amount' => 'total amount',
            'delivery_method' => 'delivery method',
            'payment_method' => 'payment method',
            'delivery_address' => 'delivery address',
            'delivery_date' => 'delivery date',
            'items.*.pizza_id' => 'pizza',
            'items.*.quantity' => 'quantity',
            'items.*.unit_price' => 'unit price',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required for the order.',
            'items.min' => 'At least one item is required for the order.',
            'items.*.pizza_id.required' => 'Pizza selection is required for each item.',
            'items.*.pizza_id.exists' => 'Selected pizza does not exist.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.quantity.max' => 'Quantity cannot exceed 10 per item.',
            'items.*.unit_price.required' => 'Unit price is required for each item.',
            'delivery_date.after' => 'Delivery date must be in the future.',
        ];
    }
}
