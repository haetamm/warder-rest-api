<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;

class UpdateUserRequest extends BaseFormRequest
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
            'name' => 'sometimes|string|max:255',
            'birth_date' => 'sometimes|date',
            'gender' => 'sometimes|in:pria,wanita,other',
            'email' => 'sometimes|email|unique:users,email,' . $this->user()->id,
            'phone_number' => 'sometimes|string|max:15',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Ambil data yang dikirimkan
            $data = $this->only(['name', 'birth_date', 'gender', 'email', 'phone_number']);

            // Pastikan minimal ada satu kolom yang diisi
            if (empty(array_filter($data))) {
                $validator->errors()->add('general', 'At least one field must be updated.');
            }
        });
    }
}
