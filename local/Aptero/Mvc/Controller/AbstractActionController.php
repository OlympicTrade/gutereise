<?php

namespace Aptero\Mvc\Controller;

use Application\Model\Page;
use Application\Model\Settings;
use Aptero\Breadcrumbs\Breadcrumbs;
use Contacts\Model\Contacts;
use Zend\Mvc\Controller\AbstractActionController as ZendActionController;
use Zend\View\Model\ViewModel;

abstract class AbstractActionController extends ZendActionController
{
    /**
     * @param null $url
     * @return ViewModel
     */
    public function generate($url = null)
    {
        if($this->isAjax()) {
            return new ViewModel();
        }

        $sm = $this->getServiceLocator();

        $page = new Page();

        if(empty($url)) {
            $uriParser = new \Zend\Uri\Uri($this->getRequest()->getUri());
            $url = $uriParser->getPath();
        }

        $page->select()->where(array(
            'url' => $url
        ));

        $page->load();

        switch($page->get('layout')) {
            case 3:
                $this->layout('layout/article');
                //$layoutType = 'article';
                break;
            default:
                $this->layout('layout/main');
                //$layoutType = 'article';
                break;
        }


        if($url == '/') {
            $this->layout('layout/index');
            //$layoutType = 'index';
        }

        $header = $page->get('header') ? $page->get('header') : $page->get('name');

        //Canonical
        $canonical = $page->get('url');

        //Meta
        $meta = (object) [
            'title'        => $page->get('title') ? $page->get('title') : $header,
            'description'  => $page->get('description'),
        ];

        $contacts = new Contacts();
        $contacts->setId(1);

        Breadcrumbs::getInstance()->initCrumbs($page);

        $this->layout()->setVariables([
            'route'        => $sm->get('Application')->getMvcEvent()->getRouteMatch(),
            'canonical'    => $canonical,
            'contacts'     => $contacts,
            'settings'     => Settings::getInstance(),
            'header'       => $header,
            'meta'         => $meta,
        ]);

        return new ViewModel([
            'header'       => $header,
            'page'         => $page,
        ]);
    }

    protected function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }

    protected function send404()
    {
        $this->generate();

		$meta = (object) array(
            'title'        => 'Страница не найдена',
            'description'  => 'Страница не найдена',
            'keywords'     => '',
        );
		
		$this->layout()->setVariable('meta', $meta);
		
        $response = $this->getResponse();
        $response->setStatusCode(404);
        $response->sendHeaders();

        return;
    }

    /**
     * @param $entity
     * @param $search
     * @param $replace
     * @param array $options
     * @return \stdClass
     */
    protected function generateMeta($entity, $search = array(), $replace = array(), $options = array())
    {
        $options = array_merge(array('prefix' => ''), $options);

        $meta = new \stdClass();
        $settings = $this->getServiceLocator()->get('Application\Model\Module')->getPlugin('settings');

        //Title
        if(!$entity || !$entity->get('title')) {
            $pattern = !empty($options['title']) ? $options['title'] : $settings->get($options['prefix'] . 'title');
        } else {
            $pattern = $entity->get('title');
        }
        $meta->title = str_replace($search, $replace, $pattern);

        $page = (int) $this->params()->fromQuery('page', 1);
        if($page > 1) {
            $meta->title .= ' — страница ' . $page;
        }

        //Keywords
        if(!$entity || !$entity->get('keywords')) {
            $pattern = !empty($options['keywords']) ? $options['keywords'] : $settings->get($options['prefix'] . 'keywords');
        } else {
            $pattern = $entity->get('keywords');
        }
        $meta->keywords = str_replace($search, $replace, $pattern);

        //Description
        if(!$entity || !$entity->get('description')) {
            $pattern = !empty($options['description']) ? $options['description'] : $settings->get($options['prefix'] . 'description');
        } else {
            $pattern = $entity->get('description');
        }
        $meta->description = str_replace($search, $replace, $pattern);

        $this->layout()->setVariable('meta', $meta);

        return $meta;
    }

    protected function addBreadcrumbs($crumbOptions)
    {
        Breadcrumbs::getInstance()->addCrumb($crumbOptions);

        return $this;
    }

    /**
     * @param $helperName
     * @param null $helperParam
     * @return string
     */
    public function viewHelper($helperName, $helperParam = null)
    {
        $helper = $this->getViewHelper($helperName);
        return $helper($helperParam);
    }

    public function getViewHelper($helperName)
    {
        return $this->getSL()->get('ViewHelperManager')->get($helperName);
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getSL()
    {
        return $this->getServiceLocator();
    }
}