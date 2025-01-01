<?php

namespace App\Http\Requests;

class RegisterRegionSellerRequest extends BaseFormRequest
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
            'province' => 'required|string|max:255',
            'regencies' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'villages' => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ];
    }
}
