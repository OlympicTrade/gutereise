<?php
namespace MuseumsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Entity\EntityHierarchy;

class Tags extends EntityHierarchy
{
    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->setTable('museums_tags');

        $this->addProperties([
            'parent'    => [],
            'name'      => [],
            'url'       => [],
            'title'     => [],
            'description'   => [],
            'count'     => [],
            'sort'      => [],
        ]);

        $this->addPlugin('background', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_tags_headers');
            $image->setFolder('museums_tags_headers');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                    'crop'   => true,
                ],
            ]);
            return $image;
        });

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();

            if(!$model->get('url')) {
                $model->set('url', \Aptero\String\Translit::url($model->get('name')));
            }

            return true;
        });

        /*$this->getEventManager()->attach(array(Entity::EVENT_PRE_DELETE), function ($event) {
            $model = $event->getTarget();

            $delete = $model->getSql()
                ->delete('museums_mtp')
                ->where(['point_id' => $model->getId()]);

            $model->execute($delete);

            return true;
        });*/
    }
}