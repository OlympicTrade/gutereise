<?php
namespace Aptero\Form\View\Helper;

use Aptero\Date\Date;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormSelect;

class FormTime extends FormSelect
{
    public function render(ElementInterface $element)
    {
        $timeFrom = Date::parseToDt($element->getOption('from'), 'time');
        $timeTo   = Date::parseToDt($element->getOption('to'), 'time');

        $daterange = new \DatePeriod($timeFrom, new \DateInterval('PT1H') ,$timeTo);
        $options = [];

        foreach($daterange as $dt){
            $options[] = $dt->format('H:i');
        }

        $element->setOption('options', $options);

        return parent::render($element);
    }

    protected function getType(ElementInterface $element)
    {
        return 'file';
    }
}
