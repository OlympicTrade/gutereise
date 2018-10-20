<?php
namespace SamplesAdmin\Model;

use Aptero\Db\Entity\Entity;

class Sample extends Entity
{
    public function __construct()
    {
        $this->setTable('samples');

        $this->addProperties([
            'name'         => [],
            'title'        => [],
            'description'  => [],
            'url'          => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('samples_images');
            $image->setFolder('samples');
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

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();

            if(!$model->get('url')) {
                $model->set('url', \Aptero\String\Translit::url($model->get('name')));
            }

            return true;
        });
    }
}