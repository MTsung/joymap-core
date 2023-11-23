<?php

namespace Mtsung\JoymapCore\Helpers;

use Faker\Generator as Faker;
use Faker\Provider\Base as FakerBase;

class Rand
{
    // 取得長度為 $length，前綴為 $prefix 的數字字串(不會有4)
    public function numberString(int $length = 8, string $prefix = ''): string
    {
        $temp = [];

        while (count($temp) < $length) {
            $n = mt_rand(0, 9);
            // 不要出現 4
            if ($n != 4) {
                $temp[] = $n;
            }
        }

        return $prefix . implode('', $temp);
    }

    // 取得長度為 $length，前綴為 $prefix 的英文數字字串(不會有4)
    public function englishNumberString(int $length = 6, string $prefix = ''): string
    {
        $faker = new Faker;
        $faker = new FakerBase($faker);
        $regex = '[ABCDEFGHJKLMNPQRSTUVWXYZ2356789]{' . $length . '}';

        return $prefix . $faker->regexify($regex);
    }
}
