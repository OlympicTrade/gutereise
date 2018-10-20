<?php
namespace CommentsAdmin\Form;

use Aptero\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CommentsEditForm extends Form
{
    public function setModel($model)
    {
        parent::setModel($model);
    }

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
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Имя',
            ],
        ]);

        $this->add([
            'name' => 'contact',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Контакты',
            ],
        ]);

        $this->add([
            'name' => 'question',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => [
                'label' => 'Вопрос',
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
            'name' => 'send',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label' => 'Ответ',
                'options' => [0 => 'Не посылать', 1 => 'Посылать']
            ],
        ]);
    }

    public function setFilters()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $inputFilter->add($factory->createInput([
            'name'     => 'name',
            'required' => false,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]));

        $this->setInputFilter($inputFilter);
    }
}