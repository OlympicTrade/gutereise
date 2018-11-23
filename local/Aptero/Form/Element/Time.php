<?php

namespace Aptero\Form\Element;

use Aptero\String\Date;
use Zend\Form\Element\Select;

class Time extends Select
{
    protected $attributes = [
        'type' => 'select',
    ];
    protected $validOptionAttributes = [
        'disabled' => true,
        'selected' => true,
        'label'    => true,
        'value'    => true,
    ];

    public function setOptions($options = [])
    {
        $options = $options + [
            'empty'     => '',
            'min'       => '00:00',
            'max'       => '24:00',
            'interval'  => '00:30',
        ];

        if($options['empty'] !== null) {
            $valOptions = [''  => $options['empty']];
        }

        $min = new \DateTime('0000-00-00 ' . $options['min']);
        $max = new \DateTime('0000-00-00 ' . $options['max']);

        list($iH, $iM) = sscanf($options['interval'], '%d:%d');
        $interval = new \DateInterval('PT' . $iH . 'H' . $iM . 'M');
        $period = new \DatePeriod($min, $interval, $max);

        foreach ($period as $dt) {
            $valOptions[$dt->format('H:i:s')] = $dt->format('H:i');
        }

        $options['options'] = $valOptions;

        return parent::setOptions($options);
    }
}
