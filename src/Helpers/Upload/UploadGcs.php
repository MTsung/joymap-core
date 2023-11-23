<?php

namespace Mtsung\JoymapCore\Helpers\Upload;

use Exception;
use Illuminate\Support\Facades\Storage;
use Mtsung\JoymapCore\Facades\Image;

class UploadGcs
{

    /**
     * @throws Exception
     */
    public function putImage($image, string $type): string
    {
        $disk = Storage::disk('gcs');

        $imageExt = $image->extension();

        $image = Image::compress($image);

        $imagePath = $type . '/' . md5($image) . '.' . $imageExt;

        if ($disk->put($imagePath, $image)) {
            $bucket = config('filesystems.disks.gcs.bucket');
            return $disk->url($bucket . '/' . $imagePath);
        }

        throw new Exception('上傳失敗');
    }

    /**
     * @throws Exception
     */
    public function delete($url): bool
    {
        $disk = Storage::disk('gcs');

        $bucket = config('filesystems.disks.gcs.bucket');

        $path = explode($bucket, $url)[1];
        if ($disk->exists($path)) {
            return $disk->delete($path);
        }

        throw new Exception('刪除失敗');
    }
}
