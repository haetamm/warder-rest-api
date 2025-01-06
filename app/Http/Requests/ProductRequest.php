<?php

namespace App\Http\Requests;

class ProductRequest extends BaseFormRequest
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
        return [
            'name' => 'required|string|min:25|max:255',
            'image_url' => 'nullable|string',
            'condition' => 'required|string|in:baru,bekas',
            'description' => 'required|string|min:260|max:5000',
            'warranty_type' => 'required|string',
            'warranty_period' => 'nullable|string',
            'price' => 'required|numeric|min:100|regex:/^\d{1,13}(\.\d{1,2})?$/',
            'stock' => 'required|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'product_weight' => 'required|numeric|min:1|max:500000',
            'shipping_insurance' => 'required|in:wajib,opsional',
        ];
    }
}
