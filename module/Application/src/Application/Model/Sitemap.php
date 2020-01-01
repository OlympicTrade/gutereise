<?php
namespace Application\Model;

class Sitemap
{
    /**
     * @var \SimpleXMLElement
     */
    protected $xml;
    protected $settings;
    protected $ns = [];

    public function __construct()
    {
        $this->xml = new \SimpleXMLElement(
            '<urlset '.
            'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '.
            'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"'.
            '></urlset>');

        $this->ns['images'] = 'http://www.google.com/schemas/sitemap-image/1.1';

        $this->xml->registerXPathNamespace('image', $this->ns['images']);

        /*if(isset($_GET['images']) && $_GET['images'] == 1) {
            $this->xml = new \SimpleXMLElement(
                '<urlset'
                . ' xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
                . ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"'
                . '></urlset>');

            $imagesNs = 'http://www.google.com/schemas/sitemap-image/1.1';
            $this->xml->registerXPathNamespace('image', $imagesNs);

        } else {
            $this->xml = new \SimpleXMLElement(
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        }*/


        $this->settings = Settings::getInstance();
    }

    public function addPage($options)
    {
        $domain = $this->settings->get('domain');

        $urlXML = $this->xml->addChild('url');

        if($options['images']) {
            foreach ($options['images'] as $image) {
                $imageXML = $urlXML->addChild('image', null, $this->ns['images']);

                $imageXML->addChild('image:loc', $domain . $image['url']);
                @$imageXML->addChild('image:title', $image['name']);
                @$imageXML->addChild('image:caption', $image['name']);
            }
        }

        if($options['lastmod']) {
            $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $options['lastmod']);

            if(!$dt) {
                $dt = \DateTime::createFromFormat('Y-m-d', $options['lastmod']);
            }

            if(!$dt) {
                throw new \Exception('Wrong "lastmod" date format');
            }

            $urlXML->addChild('lastmod', $dt->format(\DateTime::W3C));
        }

        $url = $domain . '/' . ltrim($options['loc'], '/');

        $urlXML->addChild('loc', $url);
        $urlXML->addChild('changefreq', $options['changefreq']);
        $urlXML->addChild('priority', $options['priority']);

        return $urlXML;
    }

    /**
     * @return mixed
     */
    public function getSitemap()
    {
        return $this->xml->asXML();
    }
}