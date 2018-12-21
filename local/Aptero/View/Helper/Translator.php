<?php
namespace Aptero\View\Helper;

use Translator\Model\Translator as Tr;
use Zend\View\Helper\AbstractHelper;

class Translator extends AbstractHelper
{
    public function __invoke($str)
    {
        return Tr::getInstance()->translate($str);
    }
}