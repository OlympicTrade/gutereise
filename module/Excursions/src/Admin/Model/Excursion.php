<?php
namespace ExcursionsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Entity\EntityCollection;
use Aptero\Db\Plugin\Images;
use Sync\Model\Sync;
use TranslatorAdmin\Model\Translator;

class Excursion extends Entity
{
    const NATIONALITY_ALL = 0;
    const NATIONALITY_RUS = 1; //Russians
    const NATIONALITY_FOR = 2; //Foreigners

    static public $nationalityType = [
        self::NATIONALITY_ALL => 'Лоюбая',
        self::NATIONALITY_RUS => 'Русские',
        self::NATIONALITY_FOR => 'Иностранцы',
    ];

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
            'name'              => [],
            'header'            => [],
            'header_desc'       => [],
            'preview'           => [],
            'text'              => [],
            'title'             => [],
            'duration'          => [],
            'time_from'         => [],
            'time_to'           => [],
            'description'       => [],
            'nationality'       => [],
            'url'               => [],
        ]);

        $this->addPlugin('background', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('excursions_headers');
            $image->setFolder('excursions_headers');
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
            $image->setTable('excursions_images');
            $image->setFolder('excursions');
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
            $image->setTable('excursions_images');
            $image->setFolder('excursions');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                    'crop'   => true,
                ],
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
            ]);

            $image->select()->order('sort');

            return $image;
        });

        $this->addPlugin('plan', function($model) {
            $item = new Entity();
            $item->setTable('excursions_plan');
            $item->addProperties([
                'depend'     => [],
                'icon'       => [],
                'header'     => [],
                'text'       => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
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

        $this->addPlugin('transport', function($model) {
            $item = new Entity();
            $item->setTable('excursions_transport');
            $item->addProperties([
                'depend'     => [],
                'transport_id'  => [],
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

            $model->sync();

            return true;
        });

        /*$this->getEventManager()->attach(array(Entity::EVENT_POST_INSERT, Entity::EVENT_POST_UPDATE), function ($event) {
            $model = $event->getTarget();

            return true;
        });*/

        $this->addTranslate($this);
    }

    protected function addTranslate($model)
    {
        $this->getEventManager()->attach(array(Entity::EVENT_POST_INSERT, Entity::EVENT_POST_UPDATE), function ($event) {
            $model = $event->getTarget();

            $url = '/admin/excursions/excursions/edit/?id=' . $model->getId();

            $strs = [];

            $objToTranslate = [$model];

            $plugins = ['plan'];

            foreach ($plugins as $pluginName) {
                $plugins = $model->getPlugin($pluginName);

                if(!($plugins instanceof EntityCollection)) {
                    $plugins = (array) $plugins;
                }

                foreach($plugins as $plugin) {
                    $objToTranslate[] = $plugin;
                }
            }

            foreach ($objToTranslate as $plugin) {
                foreach ($plugin as $str) {
                    if(is_string($str) && strlen($str) != mb_strlen($str, 'utf-8')) {
                        $strs[] = $str;
                    }
                }
            }

            Translator::addToTranslate($strs, $url);

            return true;
        });
    }

    public function sync()
    {
        if(!$dbId = $this->get('db_excursion_id')) {
            return;
        }

        $sync = new Sync();
        $data = $sync->getExcursionData(['id' => $dbId]);

        $dbData = new \StdClass();
        $dbData->min_time    = $data->min_time;
        $dbData->max_time    = $data->max_time;
        $dbData->duration    = $data->duration;
        $dbData->price = $data->price;

        $this->set('db_data', $dbData);

        return $this;
    }

    public function getPublicUrl()
    {
        return '/excursions/' . $this->get('url') . '/';
    }
}