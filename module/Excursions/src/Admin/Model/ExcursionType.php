<?php
namespace ExcursionsAdmin\Model;

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

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();

            if(!$model->get('url')) {
                $model->set('url', \Aptero\String\Translit::url($model->get('name')));
            }

            return true;
        });
    }
}