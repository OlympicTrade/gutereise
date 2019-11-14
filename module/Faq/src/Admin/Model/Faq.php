<?php
namespace FaqAdmin\Model;

use Aptero\Db\Entity\Entity;
use Zend\Session\Container as SessionContainer;

class Faq extends Entity
{
    public function __construct()
    {
        $this->setTable('faq');

        $this->addProperties([
            'question'     => [],
            'answer'       => [],
            'sort'         => [],
        ]);
    }
}