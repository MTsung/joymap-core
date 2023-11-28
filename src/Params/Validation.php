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
    function __construct($items = [])
    {
        $validator = Validator::make(
            $items,
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        if ($validator->fails()) {
            $firstMsg = $validator->errors()->first();
            throw new Exception('Params Validation Error: ' . $firstMsg);
        }

        parent::__construct($items);
    }
}
