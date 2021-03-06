<?php
namespace Excursions\Form;

use Aptero\Cookie\Cookie;
use Aptero\Form\Form;

use Sync\Model\DbConstants;
use Translator\Model\Translator;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class OrderForm extends CommonForm
{
    protected $excursion;

    public function __construct($excursion)
    {
        $this->excursion = $excursion;

        parent::__construct($excursion);

        $this->get('id')->setValue($excursion->getId());

        $this->add([
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Имя',
            ],
            'attributes' => [
                'class' => 'std-input'
            ]
        ]);

        $this->add([
            'name' => 'phone',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Телефон',
            ],
            'attributes' => [
                'class' => 'std-input'
            ]
        ]);

        $this->add([
            'name' => 'email',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'E-mail',
            ],
            'attributes' => [
                'class' => 'std-input'
            ]
        ]);

        if($excursion->get('transport') == \Excursions\Model\Excursion::TRANSPORT_AUTO) {
            $this->add([
                'name' => 'place_start',
                'type' => 'Zend\Form\Element\Textarea',
                'options' => [
                    'label' => 'Место начала экскурсии',
                ],
                'attributes' => [
                    'class' => 'std-textarea'
                ]
            ]);
        }

        if(MODE == 'dev') {
            $this->get('name')->setValue('Вася Пупкин');
            $this->get('phone')->setValue('9211234567');
            $this->get('email')->setValue('info@aptero.ru');

            if($excursion->get('transport') == \Excursions\Model\Excursion::TRANSPORT_AUTO) {
                $this->get('place_start')->setValue('Адрес начала экскурсии');
            }
        }
    }

    public function setFilters()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $inputFilter->add($factory->createInput([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'phone',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        if($this->excursion->get('transport') == \Excursions\Model\Excursion::TRANSPORT_AUTO) {
            $inputFilter->add($factory->createInput([
                'name'     => 'place_start',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ]));
        }

        $this->setInputFilter($inputFilter);
    }
}