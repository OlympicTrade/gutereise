<?php
namespace ExcursionsAdmin\Model;

use Aptero\Db\Entity\Entity;

class Tags extends Entity
{
    const TYPE_MAIN   = 1;
    const TYPE_TAG    = 2;

    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->setTable('excursions_tags');

        $this->addProperties([
            'name'           => [],
            'url'            => [],
            'title'          => [],
            'description'    => [],
            'type'           => [],
            'hits'           => [],
            'count'          => [],
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