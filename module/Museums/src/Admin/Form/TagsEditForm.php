<?php
namespace MuseumsAdmin\Form;

use Aptero\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class TagsEditForm extends Form
{
    public function setModel($model)
    {
        parent::setModel($model);

        $this->get('background-image')->setOptions([
            'model' => $model->getPlugin('background'),
        ]);

        $this->get('parent')->setOption('model', $this->getModel());
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
            'name' => 'parent',
            'type'  => 'Aptero\Form\Element\TreeSelect',
            'options' => [
                'label'   => 'Прикрепить к музею',
                'empty'   => '',
            ],
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
                'label' => 'url',
            ],
        ]);

        $this->add([
            'name' => 'sort',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Сортировка',
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

        $this->add([
            'name' => 'background-image',
            'type'  => 'Aptero\Form\Element\Admin\Image',
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

        $this->setInputFilter($inputFilter);
    }
}