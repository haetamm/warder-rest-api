<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;

class UpdateSellerRequest extends BaseFormRequest
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
            'shop_name' => 'sometimes|string|max:28|unique:sellers,shop_name,' .  $this->user()->sellers->id,
            'shop_domain' => 'sometimes|string|min:6|max:20|unique:sellers,shop_domain,' .  $this->user()->sellers->id,
            'province' => 'nullable|string|max:255',
            'regencies' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'villages' => 'nullable|string|max:255',
            'street_name' => 'sometimes|string|max:255',
            'postal_code' => 'sometimes|string|max:10',
            'slogan' => 'nullable|string|max:48',
            'desc' => 'nullable|string|max:140',

            'regencies' => 'nullable|string|max:255|required_with:province,district,villages',
            'district' => 'nullable|string|max:255|required_with:province,regencies,villages',
            'villages' => 'nullable|string|max:255|required_with:province,regencies,district',
        ];
    }
}
