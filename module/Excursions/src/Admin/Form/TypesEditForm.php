<?php
namespace ExcursionsAdmin\Form;

use Aptero\Form\Form;

use Museums\Model\Museum;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class TypesEditForm extends Form
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
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Название',
            ],
        ]);

        $this->add([
            'name' => 'url',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Url',
            ],
        ]);

        $this->add([
            'name' => 'title',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Description',
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