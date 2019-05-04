<?php
namespace CommentsAdmin\Form;

use CommentsAdmin\Model\Comment;
use Translator\Model\Translator;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CommentsEditForm extends \Aptero\Form\Admin\Form
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
            'name' => 'lang_code',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Язык',
                'options' =>  Translator::$codesTranscript
            ],
        ]);

        $this->add([
            'name' => 'status',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Показать на сайте',
                'options' =>  [1 => 'Да', 0 => 'Нет']
            ],
        ]);

        $this->add([
            'name' => 'depend_type',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'label'   => 'Тип комментария',
                'options' =>  [
                    Comment::TYPE_REVIEWS     => 'Отзывы о компании',
                    Comment::TYPE_EXCURSION   => 'Экскурсии',
                    Comment::TYPE_TRANSPORT   => 'Транспорт',
                ]
            ],
        ]);

        $this->add([
            'name' => 'depend_id',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'ID родительской записи',
            ],
        ]);

        $this->add([
            'name' => 'time_create',
            'type'  => 'Zend\Form\Element\Text',
            'options' => [
                'label' => 'Дата',
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
                'label' => 'Ответ на E-mail/Телефон',
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