<?php

namespace Mtsung\JoymapCore\Params;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

abstract class Validation extends Collection implements ValidationInterface
{
    /**
     * @throws Exception
     */
    public static function make($items = []): static
    {
        $validator = Validator::make(
            $items,
            static::rules(),
            static::messages(),
            static::attributes()
        );

        if ($validator->fails()) {
            $firstMsg = $validator->errors()->first();
            throw new Exception('Params Validation Error: ' . $firstMsg, 500);
        }

        return parent::make($items);
    }
}
