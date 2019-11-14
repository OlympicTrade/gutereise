<?php
namespace Faq\Model;

use Aptero\Db\Entity\Entity;

use Zend\Session\Container as SessionContainer;

use \Zend\Crypt\Password\Bcrypt;

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