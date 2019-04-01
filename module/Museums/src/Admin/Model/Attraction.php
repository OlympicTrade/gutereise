<?php
namespace MuseumsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Zend\Session\Container as SessionContainer;

class Attraction extends Entity
{
    public function __construct()
    {
        $this->setTable('museums_attractions');

        $this->addProperties([
            'name'      => [],
            'url'       => [],
            'preview'   => [],
            'text'      => [],
            'sort'      => [],
            'title'     => [],
            'description'   => [],
        ]);

        $this->addPlugin('background', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_attractions_headers');
            $image->setFolder('museums_attractions_headers');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                    'crop'   => true,
                ],
            ]);
            return $image;
        });

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_attractions_images');
            $image->setFolder('museums_attractions');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1000,
                    'height' => 800,
                ]
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new \Aptero\Db\Plugin\Images();
            $image->setTable('museums_attractions_gallery');
            $image->setFolder('museums_attractions_gallery');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                ],
                'hr' => [
                    'width'  => 1000,
                    'height' => 800,
                ]
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

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_DELETE), function ($event) {
            /** @var Entity $model */
            $model = $event->getTarget();

            $delete = $model->getSql()
                ->delete('museums_mtp')
                ->where(['point_id' => $model->getId()]);

            $model->execute($delete);

            return true;
        });
    }
}