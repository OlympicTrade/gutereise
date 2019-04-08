<?php
namespace Museums\Model;

use Aptero\Db\Entity\Entity;

class Tags extends Entity
{
    public function __construct()
    {
        $this->setTable('museums_tags');

        $this->addProperties([
            'name'      => [],
            'url'       => [],
            'title'     => [],
            'description'   => [],
            'count'     => [],
        ]);

        $this->addPlugin('background', function() {
            $image = new \Aptero\Db\Plugin\Image();
            $image->setTable('museums_tags_headers');
            $image->setFolder('museums_tags_headers');
            $image->addResolutions([
                'hr' => [
                    'width'  => 1920,
                    'height' => 400,
                    'crop'   => true,
                ],
            ]);
            return $image;
        });
    }

    public function getUrl()
    {
        return '/attractions/category/' . $this->get('url') . '/';
    }
}