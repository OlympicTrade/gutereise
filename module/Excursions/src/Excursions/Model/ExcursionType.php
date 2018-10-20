<?php
namespace Excursions\Model;

use Aptero\Db\Entity\Entity;

class ExcursionType extends Entity
{
    public function __construct()
    {
        $this->setTable('excursions_types');

        $this->addProperties([
            'name'           => [],
            'url'            => [],
            'title'          => [],
            'description'    => [],
        ]);
    }

    public function getUrl()
    {
        return '/excursions/' . $this->get('url') . '/';
    }
}