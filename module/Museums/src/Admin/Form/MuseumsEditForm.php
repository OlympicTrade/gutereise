<?php
namespace MuseumsAdmin\Form;

use Aptero\Form\Form;

use ExcursionsAdmin\Model\Excursion;
use MuseumsAdmin\Model\Attraction;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class MuseumsEditForm extends Form
{
    public function setModel($model)
    {
        parent::setModel($model);

        $this->get('image-image')->setOptions([
            'model' => $model->getPlugin('image'),
        ]);

        $this->get('background-image')->setOptions([
            'model' => $model->getPlugin('background'),
        ]);

        $this->get('images-images')->setOptions([
            'model'   => $model->getPlugin('images'),
            'product' => $model,
        ]);

        $this->get('parent')->setOption('model', $this->getModel());
        $this->get('excursions-collection')->setOption('model', $model->getPlugin('excursions'));

        //$this->get('attractions-collection')->setOption('model', $model->getPlugin('attractions'));
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

        $this->add(array(
            'name' => 'parent',
            'type'  => 'Aptero\Form\Element\TreeSelect',
            'options' => array(
                'label'   => 'Прикрепить к музею',
                'empty'   => '',
            ),
        ));

        $this->add([
            'name' => 'excursions-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'excursion_id' => [
                        'label'   => 'Экскурсия',
                        'width'   => 150,
                        'options' => new Excursion()
                    ],
                ]
            ],
        ]);

        $this->add([
            'name' => 'lat',
            'type'  => 'Zend\Form\Element\Hidden',
        ]);

        $this->add([
            'name' => 'lng',
            'type'  => 'Zend\Form\Element\Hidden',
        ]);

        $this->add(array(
            'name' => 'lat',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add([
            'name' => 'lon',
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

        $this->add([
            'name' => 'header',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Заголовок',
            ],
        ]);

        $this->add([
            'name' => 'background-image',
            'type'  => 'Aptero\Form\Element\Admin\Image',
        ]);

        $this->add([
            'name' => 'image-image',
            'type'  => 'Aptero\Form\Element\Admin\Image',
        ]);

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

        $this->add([
            'name' => 'active',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label' => 'Показать на сайте',
                'options' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
            ],
        ]);

        /*$this->add([
            'name' => 'attractions-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'attraction_id' => [
                        'label'   => 'Достопримечательности',
                        'width'   => 200,
                        'options' => new Attraction()
                    ],
                ]
            ],
        ]);*/
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