<?php

namespace Transports\Service;

use Application\Model\Sitemap;
use ApplicationAdmin\Model\Page;
use Aptero\Db\Entity\Entity;
use Aptero\Db\Entity\EntityFactory;
use Aptero\Service\AbstractService;
use Transports\Model\Transport;

class SystemService extends AbstractService
{
    public function updateSitemap(Sitemap $sitemap)
    {
        $this->itemsSitemap($sitemap);
    }

    public function itemsSitemap(Sitemap $sitemap)
    {
        $collection = Transport::getEntityCollection();
        $collection->select()
            ->columns(['id', 'url', 'name',]);

        foreach($collection as $item) {
            $images = [[
                'url'   => $item->getPlugin('image')->getImage('hr'),
                'name'  => $item->get('name'),
            ]];
            foreach ($item->getPlugin('images') as $image) {
                $images[] = [
                    'url'   => $image->getImage('hr'),
                    'name'  => $item->get('name'),
                ];
            }

            $sitemap->addPage([
                'loc'        => $item->getUrl(),
                'changefreq' => 'monthly', //monthly | weekly | daily
                'priority'   => 0.5,
                'images'     => $images,
                //'lastmod'    => $item['time_update'],
            ]);
        }
    }
}