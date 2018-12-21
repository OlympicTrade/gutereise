<?php
namespace Excursions\Model;

use Aptero\Db\Entity\Entity;

class ExcursionType extends Entity
{
    const TYPE_MAIN   = 1;
    const TYPE_TAG    = 2;

    public function __construct()
    {
        $this->setTable('excursions_types');

        $this->addProperties([
            'name'           => [],
            'url'            => [],
            'title'          => [],
            'description'    => [],
            'type'           => [],
            'hits'           => [],
        ]);
    }

    public function getUrl()
    {
        return '/excursions/' . $this->get('url') . '/';
    }
}