<?php
namespace FaqAdmin\Form;

use Aptero\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class FaqEditForm extends Form
{
    public function __construct()
    {
        parent::__construct('edit-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('autocomplete', 'off');

        $this->add([
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ]);

        $this->add([
            'name' => 'question',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => [
                'label' => 'Впорос',
            ],
        ]);

        $this->add([
            'name' => 'answer',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => [
                'label' => 'Ответ',
            ],
        ]);

        $this->add([
            'name' => 'sort',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Сортировка',
            ],
        ]);
    }

    public function setFilters()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $inputFilter->add($factory->createInput([
            'name'     => 'question',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'answer',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
        ]));

        $this->setInputFilter($inputFilter);
    }
}