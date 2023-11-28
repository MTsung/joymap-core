<?php

namespace Mtsung\JoymapCore\Params;


interface ValidationInterface
{
    public function rules(): array;

    public function attributes(): array;

    public function messages(): array;
}
