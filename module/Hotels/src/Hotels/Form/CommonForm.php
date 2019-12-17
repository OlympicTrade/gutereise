<?php
namespace Hotels\Form;

use Aptero\Cookie\Cookie;
use Aptero\Form\Form;

use Translator\Model\Translator;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CommonForm extends Form
{
    public function __construct($hotel = null)
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->setAttribute('autocomplete', 'off');

        $this->add([
            'name'  => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ]);
        $this->get('id')->setValue($hotel->getId());

        $this->add([
            'name' => 'lang_id',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Язык',
                'options' => Translator::getInstance()->getLanguages(),
            ],
            'attributes' => [
                'class' => 'std-select'
            ]
        ]);
        $this->get('lang_id')->setValue(Translator::getInstance()->getLangId());

        /*$this->add([
            'name'  => 'db_hotel_id',
            'type'  => 'Zend\Form\Element\Hidden',
        ]);*/

        $this->add([
            'name'  => 'date',
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
            'name'  => 'time',
            'type'  => 'Aptero\Form\Element\Time',
            'options' => [
                'label' => 'Время',
                'empty' => 'Время начала',
                'min'   => $hotel->get('db_data')->min_time,
                'max'   => $hotel->get('db_data')->max_time,
            ],
            'attributes' => [
                'class' => 'std-input datepicker',
                'placeholder' => 'Время',
            ]
        ]);

        $this->add([
            'name'  => 'adults',
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
            'name'  => 'children',
            'type'  => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Детей',
            ],
            'attributes' => [
                'class'     => 'std-input',
                'placeholder' =>'Детей',
                'data-min'  => '0',
                'data-max'  => '200',
            ]
        ]);

        if($data = Cookie::getCookie('commonForm', true)) {
            $this->get('adults')->setValue($data->adults);
            $this->get('children')->setValue($data->children);
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

        $inputFilter->add($factory->createInput([
            'name'     => 'children',
            'required' => false,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $this->setInputFilter($inputFilter);
    }
}