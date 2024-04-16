<?php

namespace Mtsung\JoymapCore\Traits;

trait ConverterTrait
{
    /**
     * 數字轉文字
     *
     * @param int $num
     * @return string
     */
    public function numToWord($num)
    {
        $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
        $chiUni = array('', '十', '百', '千', '萬', '十', '百', '千', '億');
        $chiStr = '';
        $num_str = (string)$num;
        $count = strlen($num_str);
        $last_flag = true; //上一個是否為0
        $zero_flag = true; //是否第一個
        $temp_num = null; //臨時數字
        $chiStr = '';//结果
        if ($count == 2) { //如是兩位數字
            $temp_num = $num_str[0];
            $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num].$chiUni[1];
            $temp_num = $num_str[1];
            $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        }else if($count > 2){
            $index = 0;
            for ($i=$count-1; $i >= 0 ; $i--) {
                $temp_num = $num_str[$i];
                if ($temp_num == 0) {
                    if (!$zero_flag && !$last_flag ) {
                        $chiStr = $chiNum[$temp_num]. $chiStr;
                        $last_flag = true;
                    }

                    if($index == 4 && $temp_num == 0){
                        $chiStr = "萬".$chiStr;
                    }
                }else{
                    if($i == 0 && $temp_num == 1 && $index == 1 && $index == 5){
                        $chiStr = $chiUni[$index%9] .$chiStr;
                    }else{
                        $chiStr = $chiNum[$temp_num].$chiUni[$index%9] .$chiStr;
                    }
                    $zero_flag = false;
                    $last_flag = false;
                }
                $index ++;
            }
        }else{
            $chiStr = $chiNum[$num_str[0]];
        }

        return $chiStr;
    }
}
