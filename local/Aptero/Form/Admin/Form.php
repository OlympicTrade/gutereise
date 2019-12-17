<?php
namespace Aptero\Form\Admin;

class Form extends \Aptero\Form\Form {
    protected $translate = false;

    public function addMeta($prefix = 'settings-')
    {
        $this->add(array(
            'name' => $prefix . 'title',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => $prefix . 'description',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Description',
            ),
        ));
    }
}