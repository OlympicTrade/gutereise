<?php
namespace TransportsAdmin\Form;

use Aptero\Form\Form;

use TransportsAdmin\Model\Transport;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class TransportsEditForm extends Form
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

        $this->get('props-collection')->setOption('model', $model->getPlugin('props'));
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
            'name' => 'props-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'name' => [
                        'label'   => 'Название',
                        'width'   => 400,
                    ],
                    'icon' => [
                        'label'   => 'Иконка',
                        'width'   => 130,
                        'options' => [
                            ''          => 'Без иконки',
                            'header'    => 'Загловок',
                            'user'      => 'Человек',
                            'time'      => 'Часы',
                            'rub'       => 'Рубль',
                        ]
                    ],
                ]
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
            'name' => 'db_transport_id',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'ID в базе данных',
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
            'name' => 'type',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label' => 'Тип',
                'options' => Transport::$types,
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
            'options' => [],
        ]);

        $this->add([
            'name' => 'images-images',
            'type'  => 'Aptero\Form\Element\Admin\Images',
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