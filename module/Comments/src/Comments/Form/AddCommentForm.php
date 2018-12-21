<?php
namespace Comments\Form;

use Aptero\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class AddCommentForm extends Form
{
    public function __construct()
    {
        parent::__construct('edit-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('autocomplete', 'off');

        $this->add([
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => [
                'placeholder' => 'Имя'
            ]
        ]);

        $this->add([
            'name' => 'contact',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => [
                'placeholder' => 'Телефон или E-mail'
            ]
        ]);

        $this->add([
            'name' => 'question',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => [
                'placeholder' => 'Вопрос или отзыв'
            ]
        ]);

        $this->add([
            'name' => 'depend_id',
            'type' => 'Zend\Form\Element\Hidden',
        ]);

        $this->add([
            'name' => 'depend_type',
            'type' => 'Zend\Form\Element\Hidden',
        ]);
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
            'name'     => 'depend_id',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'contact',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $inputFilter->add($factory->createInput([
            'name'     => 'question',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $this->setInputFilter($inputFilter);
    }
}