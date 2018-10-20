<?php
namespace Aptero\Date;

class Date
{
    /**
     * @param \DateTime $dt1
     * @param \DateTime $dt2
     * @return \DateTime
     */
    static public function dtPlusDt($dt1, $dt2) {
        $dt = clone $dt1;
        $dt->modify('+ ' . $dt2->format('H') . ' hours');
        $dt->modify('+ ' . $dt2->format('i') . ' minutes');

        return $dt;
    }

    /**
     * @param $dt1
     * @param $dt2
     * @return mixed
     */
    static public function dtMinusDt($dt1, $dt2) {
        $dt = clone $dt1;
        $dt->modify('- ' . $dt2->format('H') . ' hours');
        $dt->modify('- ' . $dt2->format('i') . ' minutes');

        return $dt;
    }

    /**
     * @param $str
     * @param string $patten
     * @return bool|\DateTime
     * @throws \Exception
     */
    static public function parseToDt($str, $patten = 'date')
    {
        switch ($patten) {
            case 'time':
                $str = str_replace('.', ':', $str);
                if(!$str) $str = '00:00:00';

                $dt = \DateTime::createFromFormat('H:i:s', $str);
                if (!$dt) {
                    $dt = \DateTime::createFromFormat('H:i', $str);
                    if (!$dt) {
                        $dt = \DateTime::createFromFormat('H', $str);
                    }
                }
                break;
            case 'date':
                if(!$str) $str = '00.00.0000';

                $dt = \DateTime::createFromFormat('d.m.Y', $str);
                if (!$dt) {
                    $dt = \DateTime::createFromFormat('Y-m-d', $str);
                }
                break;
            case 'datetime':
                $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $str);
                break;
            default:
                throw new \Exception('Unknown parsing string patten');
        }

        return $dt;
    }
}