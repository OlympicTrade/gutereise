<?php

namespace Museums\Service;

use Application\Model\Sitemap;
use Aptero\Service\AbstractService;
use Museums\Model\Museum;
use Museums\Model\Tags;

class SystemService extends AbstractService
{
    public function updateSitemap(Sitemap $sitemap)
    {
        $this->catalogSitemap($sitemap);
        $this->itemsSitemap($sitemap);
    }

    public function catalogSitemap(Sitemap $sitemap)
    {
        $items = Tags::getEntityCollection();
        $items->select()
            ->columns(['id', 'url']);

        foreach($items as $item) {
            $sitemap->addPage([
                'loc'        => $item->getUrl(),
                'changefreq' => 'monthly',
                'priority'   => 0.5,
                //'lastmod'    => $item['time_update'],
            ]);
        }
    }

    public function itemsSitemap(Sitemap $sitemap)
    {
        $collection = Museum::getEntityCollection();
        $collection->select()
            ->columns(['id', 'url', 'name',])
            ->where(['active' => 1]);

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