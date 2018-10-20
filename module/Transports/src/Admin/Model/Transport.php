<?php
namespace TransportsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Image;
use Aptero\Db\Plugin\Images;

class Transport extends Entity
{
    const TYPE_AUTO   = 1;
    const TYPE_BOAT   = 2;
    const TYPE_COPTER = 3;

    static public $types = [
        self::TYPE_AUTO     => 'Авто',
        self::TYPE_BOAT     => 'Катер',
        self::TYPE_COPTER   => 'Вертолет',
    ];

    public function __construct()
    {
        $this->setTable('transports');

        $this->addProperties([
            'name'         => [],
            'preview'      => [],
            'text'         => [],
            'type'         => [],
            'title'        => [],
            'description'  => [],
            'url'          => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new Image();
            $image->setTable('transports_images');
            $image->setFolder('transports');
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
            $image = new Images();
            $image->setTable('transports_gallery');
            $image->setFolder('transports_gallery');
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

            $image->select()->order('sort');

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