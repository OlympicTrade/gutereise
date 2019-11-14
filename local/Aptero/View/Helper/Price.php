<?php
namespace Aptero\View\Helper;

use Application\Model\Currency;
use Application\Model\Settings;
use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class Price extends AbstractHelper
{
    public function __invoke($price, $sign = true, $euro = false)
    {
        if($euro && Translator::getInstance()->isForeigners()) {
            $price = (int) ($price / Settings::getInstance()->get('euro_rate'));
        }

        $str = preg_replace('/(\d)(?=(\d\d\d)+([^\d]|$))/i', '$1 ', $price);

        if($sign) {
            $str = '<b>' . $str . '</b> ';

            switch (Currency::getInstance()->getCurrency()) {
                case 'rub':
                    $str .= '<i class="fas fa-ruble-sign"></i>';
                    break;
                case 'eur':
                    $str .= '<i class="fas fa-euro-sign"></i>';
                    break;
                case 'usd':
                    $str .= '<i class="fas fa-dollar"></i>';
                    break;
            }
        }

        return $str;
    }
}