<?php
namespace Aptero\Form;

use Zend\Form\Form as ZendForm;
use Aptero\Db\Entity\Entity;

class Form extends ZendForm {

    /**
     * @var Entity
     */
    protected $model = null;

    /**
     * @param Entity $model
     */
    public function setModel($model) {
        $this->model = $model;
    }

    public function isValid()
    {
        $this->setFilters();
        return parent::isValid();
    }

    /**
     * @return Entity
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setFilters()
    {

    }
}