<?php
namespace Excursions\Form;

use Aptero\Cookie\Cookie;
use Aptero\Form\Form;

use Sync\Model\DbConstants;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CommonForm extends Form
{
    public function __construct()
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
                'class' => 'std-input datepicker'
            ]
        ]);

        $this->add([
            'name' => 'lang_id',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label' => 'Язык',
                'options' => DbConstants::$languages,
            ],
            'attributes' => [
                'class' => 'std-input'
            ]
        ]);

        $this->add([
            'name' => 'adults',
            'type'  => 'Aptero\Form\Element\Counter',
            'options' => [
                'label' => 'Взрослых',
            ],
            'attributes' => [
                'class'     => 'std-input',
                'data-min'  => '1',
                'data-max'  => '200',
            ]
        ]);

        $this->add([
            'name' => 'children',
            'type'  => 'Aptero\Form\Element\Counter',
            'options' => [
                'label' => 'Детей',
            ],
            'attributes' => [
                'class'     => 'std-input',
                'data-min'  => '0',
                'data-max'  => '200',
            ]
        ]);

        if($data = Cookie::getCookie('commonForm', true)) {
            $this->get('adults')->setValue($data->adults);
            $this->get('children')->setValue($data->children);
            $this->get('date')->setValue($data->date);
            $this->get('lang_id')->setValue($data->lang_id);
        }
    }

    public function setFilters()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $inputFilter->add($factory->createInput([
            'name'     => 'date',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'db_excursion_id',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'lang_id',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'adults',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $this->setInputFilter($inputFilter);
    }
}