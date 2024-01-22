<?php

namespace Mtsung\JoymapCore\Http;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->ajax() || $this->wantsJson()) {
            throw new HttpResponseException(response()->json(new ApiResource([
                'msg' => $validator->errors()->first()
            ]), 422));
        }

        parent::failedValidation($validator);
    }
}
