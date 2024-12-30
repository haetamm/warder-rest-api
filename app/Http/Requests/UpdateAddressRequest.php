<?php

namespace App\Http\Requests;

class UpdateAddressRequest extends BaseFormRequest
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
            'recipient_name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:15',
            'label' => 'required|string|max:20',
            'province' => 'nullable|string|max:255',
            'regencies' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'villages' => 'nullable|string|max:255',
            'street_name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'selected' => 'required|boolean',

            'regencies' => 'nullable|string|max:255|required_with:province,district,villages',
            'district' => 'nullable|string|max:255|required_with:province,regencies,villages',
            'villages' => 'nullable|string|max:255|required_with:province,regencies,district',
        ];
    }
}
