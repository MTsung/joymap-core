<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Model as baseModel;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;

class Model extends baseModel
{
    use SerializeDateTrait;
}
