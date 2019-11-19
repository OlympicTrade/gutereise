<?php
namespace ExcursionsAdmin\Model;

use Aptero\Db\Entity\Entity;
use Aptero\Db\Entity\EntityCollection;
use Aptero\Db\Plugin\Images;
use Sync\Model\Sync;
use TranslatorAdmin\Model\Translator;
use Zend\Db\Sql\Expression;

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

    const TYPE_EXCURSION = 0;
    const TYPE_TOUR      = 1;

    static public $types = [
        self::TYPE_EXCURSION => 'Экскурсия',
        self::TYPE_TOUR => 'Тур',
    ];

    public function __construct()
    {
        $this->setTable('excursions');

        $this->addProperties([
            'db_excursion_id'   => [],
            'db_data'           => ['type' => 'json'],
            'options'           => ['type' => 'json'],
            'name'              => [],
            'header'            => [],
            'header_desc'       => [],
            'preview'           => [],
            'place_and_time'    => [],
            'text'              => [],
            'title'             => [],
            'duration'          => [],
            'description'       => [],
            'nationality'       => [],
            'url'               => [],
            'type'              => [],
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

        $this->addPlugin('reco', function($model) {
            $item = new Entity();
            $item->setTable('excursions_reco');
            $item->addProperties([
                'depend'     => [],
                'excursion_id'  => [],
                'time'       => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->addPlugin('pricetable', function($model) {
            $item = new Entity();
            $item->setTable('excursions_pricetable');
            $item->addProperties([
                'depend'     => [],
                'text'       => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        /*$this->addPlugin('transport', function($model) {
            $item = new Entity();
            $item->setTable('excursions_transport');
            $item->addProperties([
                'depend'     => [],
                'transport_id'  => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });*/

        $this->addPlugin('tags', function($model) {
            $item = new Entity();
            $item->setTable('excursions_ett');
            $item->addProperties([
                'depend'     => [],
                'tag_id'     => [],
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

        $this->getEventManager()->attach(array(Entity::EVENT_POST_INSERT, Entity::EVENT_POST_UPDATE), function ($event) {
            $select = $this->getSql()->select();
            $select->from(['t' => 'excursions_ett'])
                ->columns(['tag_id', 'count' => new Expression('COUNT(*)')])
                ->group('tag_id');

            $res = $this->execute($select);

            foreach ($res as $row) {
                if($tag = (new Tags(['id' => $row['tag_id']]))->load()) {
                    $tag->set('count', $row['count'])->save();
                }
            }

            return true;
        });

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

        $data = $data->days;

        /*if(count($data->days) == 1) {
            $firstDay = $data->days[0];
            $data->duration = $firstDay->duration;
            $data->min_time = $firstDay->min_time;
            $data->max_time = $firstDay->max_time;
            unset($data->days);
        }*/

		$this->set('db_data', $data);

        return $this;
    }

    public function getPublicUrl()
    {
        return '/excursions/' . $this->get('url') . '/';
    }

    public function getUrl()
    {
        return '/admin/excursions/excursions/edit/?id=' . $this->getId();
    }
}