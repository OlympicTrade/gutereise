<?php
namespace Aptero\Form\Element;

use Aptero\Date\Date;
use Zend\Form\Element;

class Time extends Element\Select
{
    protected $validOptionAttributes = [
        'disabled' => true,
        'selected' => true,
        'label'    => true,
        'value'    => true,
    ];

    protected $attributes = array(
        'type' => 'select',
    );


    public function setOptions($options)
    {
        $timeFrom = Date::parseToDt($options['from'], 'time');
        $timeTo   = Date::parseToDt($options['to'], 'time');

        $daterange = new \DatePeriod($timeFrom, new \DateInterval('PT30M') ,$timeTo);
        $sOptions = [];

        if(isset($options['empty']) && $options['empty'] !== null) {
            $sOptions[''] = $options['empty'];
        }

        foreach($daterange as $dt){
            $sOptions[$dt->format('H:i:s')] = $dt->format('H:i') . ($options['duration'] ? ' - ' . $dt->modify($options['duration'])->format('H:i') : '');
        }

        $this->setOption('options', $sOptions);

        $options['options'] = $sOptions;

        return parent::setOptions($options);
    }
}
