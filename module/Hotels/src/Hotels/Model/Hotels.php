<?php
namespace Hotels\Model;

use Aptero\Db\Entity\Entity;

class Hotel extends Entity
{
    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->setTable('hotels');

        $this->addProperties([
            'url'               => [],
        ]);
    }

    public function getUrl()
    {
        return '/hotels/' . $this->get('url') . '/';
    }
}