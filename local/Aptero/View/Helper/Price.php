<?php
namespace Aptero\View\Helper;

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
            $lang = Translator::getInstance()->getLangCode();
            $str = '<b>' . $str . '</b> ' . ($lang == 'ru' ? ' <i class="fas fa-ruble-sign"></i>' : ' <i class="fas fa-euro-sign"></i>');
        }

        return $str;
    }
}