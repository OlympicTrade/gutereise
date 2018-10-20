<?php
namespace ExcursionsAdmin\Form;

use Aptero\Form\Form;

use ExcursionsAdmin\Model\Excursion;
use ExcursionsAdmin\Model\ExcursionType;
use Museums\Model\Museum;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ExcursionsEditForm extends Form
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

        $this->get('museums-collection')->setOption('model', $model->getPlugin('museums'));
        $this->get('types-collection')->setOption('model', $model->getPlugin('types'));
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
            'name' => 'db_excursion_id',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'ID в базе данных',
            ],
        ]);

        $this->add([
            'name' => 'transport',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Тип транспорта',
                'options' => Excursion::$transportType,
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
            'name' => 'header',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Заголовок',
            ],
        ]);

        $this->add([
            'name' => 'header_desc',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Заголовок 2 стр.',
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
            'name' => 'preview',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => [
                'label' => 'Кратк. описание',
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
            'name' => 'text',
            'type'  => 'Zend\Form\Element\Textarea',
            'attributes' => [
                'class' => 'editor',
                'id'    => 'page-text'
            ],
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
            'name' => 'museums-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'museum_id' => [
                        'label'   => 'Музеи',
                        'width'   => 150,
                        'options' => new Museum()
                    ],
                ]
            ],
        ]);

        $this->add([
            'name' => 'types-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'type_id' => [
                        'label'   => 'Тип',
                        'width'   => 150,
                        'options' => new ExcursionType()
                    ],
                ]
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