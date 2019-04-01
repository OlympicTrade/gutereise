<?php
namespace Aptero\String;

class Numbers
{
    /**
     * example \Aptero\String\Numbers::declension($number, ['яблоко', 'яблока', 'яблок'])
     *
     * @param $number
     * @param $endingArray
     * @param $showNbr
     * @return string
     */
    static public function declension($number, $endingArray, $showNbr = true)
    {
        $str = ($showNbr ? $number . ' ' : '');

        $number = $number % 100;
        if ($number >= 11 && $number <= 19) {
            $str .= $endingArray[2];
        } else {
            switch ($number % 10)
            {
                case (1): $str .= $endingArray[0]; break;
                case (2):
                case (3):
                case (4): $str .= $endingArray[1]; break;
                default: $str .= $endingArray[2];
            }
        }

        return $str;
    }
}