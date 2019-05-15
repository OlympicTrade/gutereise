<?php
namespace Museums\Model;

use Application\Model\Content;
use Aptero\Db\Entity\EntityHierarchy;
use Aptero\Db\Plugin\Images;
use Excursions\Model\Excursion;

class Museum extends EntityHierarchy
{
    public function __construct()
    {
        $this->setTable('museums');

        $this->addProperties([
            'parent'   => [],
            'name'     => [],
            'header'   => [],
            'url'      => [],
            'url_path' => [],
            'preview'  => [],
            'text'     => [],
            'lat'      => [],
            'lng'      => [],
            'title'    => [],
            'description'   => [],
            'active' => [],
        ]);

        $this->addPlugin('content', function($model) {
            $content = Content::getEntityCollection();
            $content->select()->where([
                'module'    => 'blog',
                'depend'    => $model->getId(),
            ])->order('sort');

            return $content;
        });

        $this->addPlugin('excursions', function($model) {
            $items = Excursion::getEntityCollection();
            $items->select()
                ->join(['em' => 'excursions_museums'], 'em.depend = t.id', [])
                ->where(['em.museum_id' => $model->getId()]);

            return $items;
        });

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
            $image->setTable('museums_images');
            $image->setFolder('museums');
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
            $image = new Images();
            $image->setTable('museums_gallery');
            $image->setFolder('museums_gallery');
            $image->addResolutions([
                'g' => [
                    'width'  => 900,
                    'height' => 500,
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

            $image->select()->order('sort');

            return $image;
        });
    }

    public function getUrl()
    {
        return '/attractions/' . trim($this->get('url_path'), '/') . '/';
    }
}