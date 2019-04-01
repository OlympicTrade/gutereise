<?php
namespace Museums\Model;

use Aptero\Db\Entity\Entity;

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
            $image->setTable('museums_headers');
            $image->setFolder('museums_headers');
            $image->addResolutions([
                'h' => [
                    'width'  => 1920,
                    'height' => 440,
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
                'g' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'm' => [
                    'width'  => 500,
                    'height' => 320,
                    'crop'   => true,
                ],
                'r' => [
                    'width'  => 965,
                    'height' => 575,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1780,
                    'height' => 970,
                ]
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new \Aptero\Db\Plugin\Images();
            $image->setTable('museums_attractions_gallery');
            $image->setFolder('museums_attractions_gallery');
            $image->addResolutions([
                'g' => [
                    'width'  => 900,
                    'height' => 500,
                    'crop'   => true,
                ],
                'm' => [
                    'width'  => 500,
                    'height' => 320,
                    'crop'   => true,
                ],
                'r' => [
                    'width'  => 965,
                    'height' => 575,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1780,
                    'height' => 970,
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
        return '/attractions/attraction/' . $this->get('url') . '/';
    }
}