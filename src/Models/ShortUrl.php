<?php

namespace Mtsung\JoymapCore\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    use HasFactory;

    protected $table = 'short_url';

    protected $guarded = ['id'];

    protected $appends = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->md5 = md5($model->url);

            $exists = static::query()->where('md5', $model->md5)->get();
            foreach ($exists as $item) {
                if ($item->url === $model->url) {
                    $model->id = $item->id;
                    $model->code = $item->code;
                    return false;
                }
            }

            $i = 0;
            do {
                $code = Str::random(8);
                if ($i++ > 100) {
                    throw new Exception('code random fail.');
                }
            } while (static::query()->where('code', $code)->exists());

            $model->code = $code;
        });
    }

    static function add($url): string
    {
        $model = static::query()->create(['url' => $url]);

        return config('joymap.domain.www') . '/i/' . $model->code;
    }
}
