<?php
namespace Aptero\Breadcrumbs;

use Application\Model\Page;

class Breadcrumbs
{
    static protected $instance;
    static public function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected $breadcrumbs = [];

    public function getCrumbs()
    {
        return $this->breadcrumbs;
    }

    /**
     * @param Page $page
     * @return $this
     */
    public function initCrumbs(Page $page)
    {
        if(!$page) {
            return $this;
        }

        do {
            $this->addCrumb(['url' => $page->get('url'), 'name' => $page->get('name')], true);
        } while ($page = $page->getParent());

        $this->addCrumb(['url' => '/', 'name' => 'Home'], true);

        return $this;
    }

    /**
     *
     * ['url' => 'string', 'name' => 'crumb name']
     *
     * @param $options
     * @param bool $toStart
     * @return $this
     */
    public function addCrumb($options, $toStart = false)
    {
        if($toStart) {
            array_unshift($this->breadcrumbs, $options);
        } else {
            $this->breadcrumbs[] = $options;
        }

        return $this;
    }

    public function addCrumbs($crumbs)
    {
        foreach ($crumbs as $crumbOpts) {
            $this->addCrumb($crumbOpts);
        }

        return $this;
    }
}