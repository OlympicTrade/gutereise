<?php
namespace ExcursionsAdmin\Form;

use Aptero\Form\Form;

use ExcursionsAdmin\Model\Excursion;
use ExcursionsAdmin\Model\Tags;
use MuseumsAdmin\Model\Museum;
use TransportsAdmin\Model\Transport;
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

        $this->get('background-image')->setOptions([
            'model' => $model->getPlugin('background'),
        ]);

        $this->get('images-images')->setOptions([
            'model'   => $model->getPlugin('images'),
            'product' => $model,
        ]);

        $this->get('pricetable-collection')->setOption('model', $model->getPlugin('pricetable'));
        $this->get('museums-collection')->setOption('model', $model->getPlugin('museums'));
        $this->get('reco-collection')->setOption('model', $model->getPlugin('reco'));
        $this->get('tags-collection')->setOption('model', $model->getPlugin('tags'));
        $this->get('plan-collection')->setOption('model', $model->getPlugin('plan'));
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

        /*$this->add([
            'name' => 'transport',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Тип транспорта',
                'options' => Excursion::$transportType,
            ],
        ]);*/

        $this->add([
            'name' => 'nationality',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Национальность',
                'options' => Excursion::$nationalityType,
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
            'name' => 'place_and_time',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Время и место начала',
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
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Textarea',
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
            'name' => 'background-image',
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
                        'label'   => 'Достопримечательность',
                        'width'   => 150,
                        'sort'    => 'name',
                        'options' => new Museum()
                    ],
                ]
            ],
        ]);

        $this->add([
            'name' => 'reco-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'excursion_id' => [
                        'label'   => 'Экскурсия',
                        'width'   => 150,
                        'sort'    => 'name',
                        'options' => new Excursion()
                    ],
                ]
            ],
        ]);

        /*$this->add([
            'name' => 'transport-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'transport_id' => [
                        'label'   => 'Транспорт',
                        'width'   => 150,
                        'options' => new Transport()
                    ],
                ]
            ],
        ]);*/

        $this->add([
            'name' => 'pricetable-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'text' => [
                        'label'   => 'В стоимость включено',
                        'width'   => 250,
                    ],
                ]
            ],
        ]);

        $this->add([
            'name' => 'tags-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'tag_id' => [
                        'label'   => 'Тип',
                        'width'   => 150,
                        'sort'    => 'name',
                        'options' => new Tags()
                    ],
                ]
            ],
        ]);

        $this->add([
            'name' => 'plan-collection',
            'type'  => 'Aptero\Form\Element\Admin\Collection',
            'options' => [
                'options'      => [
                    'icon' => [
                        'label'   => 'Иконка',
                        'width'   => 130,
                        'options' => [
                            'museum' => 'Музей',
                            'park'   => 'Парк',
                            'marker' => 'Флаг',
                            'cafe'   => 'Кафе',
                            'auto'   => 'Авто',
                            'anchor' => 'Катер',
                        ],
                    ],
                    'header' => [
                        'label'   => 'Заголовок',
                        'width'   => 200,
                    ],
                    'text' => [
                        'label'   => 'Описание',
                        'width'   => 600,
                        'type'    => 'textarea'
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