<?php
namespace MuseumsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Zend\Session\Container as SessionContainer;

class Museum extends Entity
{
    public function __construct()
    {
        $this->setTable('museums');

        $this->addProperties([
            'name'    => [],
            'url'     => [],
            'header2' => [],
            'preview' => [],
            'text'    => [],
            'lat'     => ['default' => '59.927725'],
            'lng'     => ['default' => '30.325141'],
            'title'   => [],
            'description'   => [],
        ]);

        $this->addPlugin('points', function($model) {
            $item = new Entity();
            $item->setTable('museums_mtp');
            $item->addProperties([
                'depend'      => [],
                'point_id' => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_images');
            $image->setFolder('museums');
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
            $image->setTable('museums_gallery');
            $image->setFolder('museums_gallery');
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
    }
}