<?php

namespace Mtsung\JoymapCore\Models;

use Illuminate\Database\Eloquent\Model as baseModel;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Traits\SerializeDateTrait;

class Model extends baseModel
{
    use SerializeDateTrait;

    // 若 table 真的有駝峰命名，要把欄位加到 $guarded
    public function __set($key, $value)
    {
        parent::__set(Str::snake($key), $value);
    }

    // 讓 $model->from_source 可以用 $model->fromSource 也拿到值
    public function __get($key)
    {
        $val = parent::__get($key);

        if (is_null($val)) {
            return parent::__get(Str::snake($key));
        }

        return $val;
    }
}
