<?php

namespace Mtsung\JoymapCore\Http;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    // 有些 request 沒給 ajax header 這個參數可強制回應 json
    protected bool $forceAjax = false;

    protected function failedValidation(Validator $validator)
    {
        if ($this->ajax() || $this->wantsJson() || $this->forceAjax) {
            throw new HttpResponseException(response()->json(new ApiResource([
                'msg' => $validator->errors()->first()
            ]), 422));
        }

        parent::failedValidation($validator);
    }
}
