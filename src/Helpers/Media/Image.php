<?php

namespace Mtsung\JoymapCore\Helpers\Media;


class Image
{
    // 壓縮
    public function compress($image): string
    {
        \Intervention\Image\Facades\Image::configure(['driver' => 'imagick']);

        $img = \Intervention\Image\Facades\Image::make($image)->orientate();
        $w = $img->width();

        // resize
        if ($w >= 1920) {
            $newWeight = (int)round($w / 2);
            $img->resize($newWeight, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // 統一在壓縮剩下75%
        return (string)$img->encode(null, 75);
    }
}
