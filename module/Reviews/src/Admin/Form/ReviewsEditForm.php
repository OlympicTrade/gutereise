<?php
namespace ReviewsAdmin\Form;

use Aptero\Form\Form;

use ReviewsAdmin\Model\Review;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ReviewsEditForm extends Form
{
    public function __construct()
    {
        parent::__construct('edit-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('autocomplete', 'off');

        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Автор',
            ),
        ));

        $this->add(array(
            'name' => 'review',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Комментарий',
            ),
        ));

        $this->add(array(
            'name' => 'answer',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Ответ',
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'options' => Review::$statuses,
                'label' => 'Статус',
            ),
        ));

        $this->add(array(
            'name' => 'stars',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'options' => [5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1],
                'label' => 'Звезды',
            ),
        ));
    }

    public function setFilters()
    {
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $inputFilter->add($factory->createInput(array(
            'name'     => 'name',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        )));

        $this->setInputFilter($inputFilter);
    }
}