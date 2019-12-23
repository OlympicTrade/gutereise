<?php
namespace Aptero\View\Helper;

use Application\Model\Currency;
use Application\Model\Settings;
use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class Price extends AbstractHelper
{
    public function __invoke($price, $sign = true, $nbr = true,  $currency = null)
    {
        $currency = $currency ?? Currency::getInstance()->getCurrency();

        switch ($currency) {
            case 'eur':
                $rate = Settings::getInstance()->get('euro_rate');
                break;
            case 'usd':
                $rate = Settings::getInstance()->get('usd_rate');
                break;
            default:
                $rate = 1;
        }

        $str = '';

        if($nbr) {
            $price = (int)($price / $rate);
            $str = '<span>' . preg_replace('/(\d)(?=(\d\d\d)+([^\d]|$))/i', '$1 ', $price);
        }

        if($sign) {
            $str .= ' ';

            switch (Currency::getInstance()->getCurrency()) {
                case 'rub':
                    $str .= '<i class="fal fa-ruble-sign"></i>';
                    break;
                case 'eur':
                    $str .= '<i class="fal fa-euro-sign"></i>';
                    break;
                case 'usd':
                    $str .= '<i class="fal fa-dollar"></i>';
                    break;
            }
        }

        $str .= '</span>';

        return $str;
    }
}