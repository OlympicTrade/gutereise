<?php
namespace Museums\Model;

use Aptero\Db\Entity\Entity;
use Excursions\Model\Excursion;

class Museum extends Entity
{
    public function __construct()
    {
        $this->setTable('museums');

        $this->addProperties([
            'name'    => [],
            'header2' => [],
            'url'     => [],
            'preview' => [],
            'text'    => [],
            'lat'     => [],
            'lng'     => [],
            'title'   => [],
            'description'   => [],
        ]);

        $this->addPlugin('points', function($model) {
            $points = Point::getEntityCollection();
            $points->select()
                ->join(['mtp' => 'museums_mtp'], 'mtp.point_id = t.id', [])
                ->where(['mtp.depend' => $model->getId()]);

            return $points;
        });

        $this->addPlugin('excursions', function($model) {
            $excursion = Excursion::getEntityCollection();
            $excursion->select()
                ->join(['em' => 'excursions_museums'], 'em.depend = t.id', [])
                ->where(['em.museum_id' => $model->getId()]);

            return $excursion;
        });

        $this->addPlugin('image', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_images');
            $image->setFolder('museums');
            $image->addResolutions([
                'm' => [
                    'width'  => 400,
                    'height' => 300,
                    'crop'   => true,
                ],
                's' => [
                    'width'  => 160,
                    'height' => 110,
                    'crop'   => true,
                ],
            ]);

            return $image;
        });
    }

    public function getUrl()
    {
        return '/museums/' . $this->get('url') . '/';
    }
}