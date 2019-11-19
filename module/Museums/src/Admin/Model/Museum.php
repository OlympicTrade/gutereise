<?php
namespace MuseumsAdmin\Model;

use ApplicationAdmin\Model\Content;
use Aptero\Db\Entity\Entity;
use Aptero\Db\Entity\EntityHierarchy;
use Zend\Db\Sql\Expression;

class Museum extends EntityHierarchy
{
    const TYPE_ARTICLE  = 1;
    const TYPE_CATEGORY = 2;

    public function __construct()
    {
        $this->setTable('museums');

        $this->addProperties([
            'parent'    => [],
            'name'      => [],
            'header'    => [],
            'url'       => [],
            'url_path'  => [],
            'header2'   => [],
            'preview'   => [],
            'text'      => [],
            'lat'       => ['default' => '59.927725'],
            'lng'       => ['default' => '30.325141'],
            'title'     => [],
            'description' => [],
            'active' => [],
        ]);

        $this->addPlugin('excursions', function($model) {
            $item = new Entity();
            $item->setTable('museums_excursions');
            $item->addProperties([
                'depend'        => [],
                'excursion_id'  => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->addPlugin('content', function($model) {
            $content = Content::getEntityCollection();
            $content->select()->where([
                'module'    => 'blog',
                'depend'    => $model->getId(),
            ])->order('sort');

            return $content;
        });

        $this->addPlugin('tags', function($model) {
            $item = new Entity();
            $item->setTable('museums_mtt');
            $item->addProperties([
                'depend'   => [],
                'tag_id'   => [],
            ]);
            $catalog = $item->getCollection()->getPlugin();
            $catalog->setParentId($model->getId());

            return $catalog;
        });

        $this->addPlugin('background', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_headers');
            $image->setFolder('museums_headers');
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
            $image->setTable('museums_images');
            $image->setFolder('museums');
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
            $image = new \Aptero\Db\Plugin\Images();
            $image->setTable('museums_gallery');
            $image->setFolder('museums_gallery');
            $image->addResolutions([
                'a' => [
                    'width'  => 162,
                    'height' => 162,
                ],
            ]);

            return $image;
        });

        $this->addPropertyFilterIn('parent', function($model, $parentId) {
            $parentId = (int) $parentId;
            $model->setParentId($parentId);
            return $parentId;
        });

        $this->addPropertyFilterIn('parent', function($model, $url) {
            return \Aptero\String\Translit::url($url);
        });

        //URL
        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();

            if(!$model->get('url')) {
                $model->set('url', \Aptero\String\Translit::url($model->get('name')));
            }

            return true;
        });

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();

            $url_path = $model->get('url');
            $parent = $model->getParent();
            while($parent) {
                $url_path = $parent->get('url') . '/' . $url_path;
                $parent = $parent->getParent();
            }

            $url_path = !$url_path ? '/' : '/' . $url_path . '/';

            $model->set('url_path', trim($url_path));

            return true;
        });

        $this->getEventManager()->attach(array(Entity::EVENT_POST_INSERT, Entity::EVENT_POST_UPDATE), function ($event) {
            $select = $this->getSql()->select();
            $select->from(['t' => 'museums_mtt'])
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
    }

    public function getPublicUrl()
    {
        return '/attractions' . $this->get('url_path');
    }
}