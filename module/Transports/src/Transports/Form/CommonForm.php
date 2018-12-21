<?php
namespace Transports\Form;

use Translator\Model\Translator;
use Aptero\Cookie\Cookie;
use Aptero\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CommonForm extends Form
{
    public function __construct($excursion = null)
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->setAttribute('autocomplete', 'off');

        $this->add([
            'name' => 'db_excursion_id',
            'type'  => 'Zend\Form\Element\Hidden',
        ]);

        $this->add([
            'name' => 'date',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Дата',
            ],
            'attributes' => [
                'class' => 'std-input datepicker',
                'placeholder' => 'Дата',
            ]
        ]);

        $this->add([
            'name' => 'time',
            'type'  => 'Aptero\Form\Element\Time',
            'options' => [
                'label' => 'Время',
                'empty' => 'Время начала',
            ],
            'attributes' => [
                'class' => 'std-input datepicker',
                'placeholder' => 'Время',
            ]
        ]);

        $this->add([
            'name' => 'adults',
            'type'  => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Взрослых',
            ],
            'attributes' => [
                'class'     => 'std-input',
                'placeholder' => 'Взрослых',
                'data-min'  => '1',
                'data-max'  => '200',
            ]
        ]);

        $this->add([
            'name' => 'duration',
            'type'  => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Длительность',
            ],
            'attributes' => [
                'class'     => 'std-input',
                'placeholder' => 'Аренда (часов)',
                'data-min'  => '1',
                'data-max'  => '200',
            ]
        ]);

        if($data = Cookie::getCookie('commonForm', true)) {
            $this->get('adults')->setValue($data->adults);
            $this->get('duration')->setValue($data->duration);
            $this->get('date')->setValue($data->date);
            $this->get('time')->setValue($data->time);
        }
    }

    public function setFilters()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $inputFilter->add($factory->createInput([
            'name'     => 'date',
            'required' => false,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'time',
            'required' => false,
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'adults',
            'required' => false,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $this->setInputFilter($inputFilter);
    }
}