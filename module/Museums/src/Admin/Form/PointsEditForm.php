<?php
namespace MuseumsAdmin\Form;

use Aptero\Form\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class PointsEditForm extends Form
{
    public function setModel($model)
    {
        parent::setModel($model);

        $this->get('image-image')->setOptions([
            'model' => $model->getPlugin('image'),
        ]);

        $this->get('images-images')->setOptions([
            'model'   => $model->getPlugin('images'),
            'product' => $model,
        ]);
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
                'label' => 'Название',
            ],
        ]);

        $this->add([
            'name' => 'preview',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => [
                'label' => 'Кратк. описание',
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

        $this->add(array(
            'name' => 'image-image',
            'type'  => 'Aptero\Form\Element\Admin\Image',
        ));

        $this->add([
            'name' => 'images-images',
            'type'  => 'Aptero\Form\Element\Admin\Images',
        ]);

        $this->add([
            'name' => 'text',
            'type'  => 'Zend\Form\Element\Textarea',
            'attributes' => [
                'class' => 'editor',
                'id'    => 'page-text'
            ],
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