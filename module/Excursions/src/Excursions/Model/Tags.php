<?php
namespace Excursions\Model;

use Aptero\Db\Entity\Entity;

class Tags extends Entity
{
    const TYPE_MAIN   = 1;
    const TYPE_TAG    = 2;

    public function __construct()
    {
        $this->setTable('excursions_tags');

        $this->addProperties([
            'name'           => [],
            'url'            => [],
            'title'          => [],
            'description'    => [],
            'type'           => [],
            'background'     => [],
            'hits'           => [],
            'count'          => [],
        ]);
    }

    public function getUrl()
    {
        return '/excursions/category/' . $this->get('url') . '/';
    }
}