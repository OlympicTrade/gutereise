<?php
namespace ExcursionsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Plugin\Images;
use Sync\Model\Sync;

class Excursion extends Entity
{
    const TRANSPORT_AUTO = 1;
    const TRANSPORT_WALK = 2;

    static public $transportType = [
        self::TRANSPORT_AUTO => 'С трансфером',
        self::TRANSPORT_WALK => 'Пешеходная',
    ];

    public function __construct()
    {
        $this->setTable('excursions');

        $this->addProperties([
            'db_excursion_id'   => [],
            'db_data'           => ['type' => 'json'],
            'transport'         => [],
            'name'              => [],
            'header'            => [],
            'header_desc'       => [],
            'preview'           => [],
            'text'              => [],
            'title'             => [],
            'description'       => [],
            'url'               => [],
        ]);

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('excursions_images');
            $image->setFolder('excursions');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                    'crop'   => true,
                ],
                'hr' => [
                    'width'  => 1900,
                    'height' => 900,
                ]
            ]);

            return $image;
        });

        $this->addPlugin('images', function() {
            $image = new Images();
            $image->setTable('excursions_gallery');
            $image->setFolder('excursions_gallery');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                ],
                'hr' => [
                    'width'  => 1900,
                    'height' => 900,
                ]
            ]);

            $image->select()->order('sort');

            return $image;
        });

        $this->addPlugin('museums', function($model) {
            $item = new Entity();
            $item->setTable('excursions_museums');
            $item->addProperties([
                'depend'     => [],
                'museum_id'  => [],
                'time'       => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->addPlugin('types', function($model) {
            $item = new Entity();
            $item->setTable('excursions_ett');
            $item->addProperties([
                'depend'     => [],
                'type_id'    => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();

            if(!$model->get('url')) {
                $model->set('url', \Aptero\String\Translit::url($model->get('name')));
            }

            return true;
        });

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();
            $model->updateFromDb();

            return true;
        });
    }

    protected function updateFromDb()
    {
        $sync = new Sync();
        $data = $sync->getExcursionData(['id' => $this->getId()]);
        $this->set('duration', $data->duration);

        $dbData = new \StdClass();
        $dbData->min_time = $data->min_time;
        $dbData->max_time = $data->max_time;
        $dbData->duration = $data->duration;
        $dbData->price_total = $data->price->summary->income;
        $dbData->price_adult = $data->price->summary->adult;

        $this->set('db_data', $dbData);
    }
}