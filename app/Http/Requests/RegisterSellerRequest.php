<?php

namespace App\Http\Requests;

class RegisterSellerRequest extends BaseFormRequest
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
            'shop_name' => 'required|string|max:28|unique:sellers,shop_name',
            'shop_domain' => 'required|string|min:6|max:20|unique:sellers,shop_domain',
        ];
    }
}
