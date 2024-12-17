<?php

namespace App\Http\Requests;

use App\Helpers\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            JsonResponse::respondFail($validator->getMessageBag(), 422)
        );
    }
}
