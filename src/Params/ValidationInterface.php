<?php

namespace Mtsung\JoymapCore\Params;


interface ValidationInterface
{
    public static function rules(): array;

    public static function attributes(): array;

    public static function messages(): array;
}
