<?php
namespace Museums\Model;

use Aptero\Db\Entity\Entity;

class Point extends Entity
{
    public function __construct()
    {
        $this->setTable('museums_points');

        $this->addProperties([
            'name'      => [],
            'url'       => [],
            'preview'   => [],
            'text'      => [],
            'sort'      => [],
            'title'     => [],
            'description'   => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_points_images');
            $image->setFolder('museums_points');
            $image->addResolutions([
                'm' => [
                    'width'  => 400,
                    'height' => 300,
                    'crop'   => true,
                ]
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new \Aptero\Db\Plugin\Images();
            $image->setTable('museums_points_gallery');
            $image->setFolder('museums_points_gallery');
            $image->addResolutions([
                'm' => [
                    'width'  => 400,
                    'height' => 300,
                    'crop'   => true,
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

    public function getUrl()
    {
        return '/museums/point/' . $this->get('url') . '/';
    }
}