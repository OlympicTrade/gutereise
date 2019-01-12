<?php

namespace Aptero\Mvc\Controller;

use Application\Model\Page;
use Aptero\Breadcrumbs\Breadcrumbs;
use Contacts\Model\Contacts;
use User\Service\AuthService;
use Zend\Log\Writer\ChromePhp;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

abstract class AbstractMobileActionController extends AbstractActionController
{
    public function generate($url = null)
    {
        if($this->isAjax()) {
            return new ViewModel();
        }

        $sm = $this->getServiceLocator();

        $settings = $sm->get('Settings');

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
                $this->layout('layout/mobile/article');
                break;
            default:
                $this->layout('layout/mobile/main');
                break;
        }

        if($url == '/') {
            $this->layout('layout/mobile/index');
        }

        $header = $page->get('header') ? $page->get('header') : $page->get('name');

        //Canonical
        $canonical = $page->get('url');

        //Meta
        $meta = (object) array(
            'title'        => ($page->get('title') ? $page->get('title') : $header),
            'description'  => $page->get('description'),
            'keywords'     => $page->get('keywords'),
        );

        //Micro Formats
        $contacts = new Contacts();
        $contacts->setId(1);

        Breadcrumbs::getInstance()->initCrumbs($page);

        $this->layout()->setVariables(array(
            'route'        => $sm->get('Application')->getMvcEvent()->getRouteMatch(),
            'canonical'    => $canonical,
            'contacts'     => $contacts,
            'settings'     => $settings,
            'header'       => $header,
            'meta'         => $meta,
        ));

        return new ViewModel(array(
            'header'       => $header,
            'page'         => $page,
        ));
    }

    protected function addBreadcrumbs($crumbOptions)
    {
        Breadcrumbs::getInstance()->addCrumb($crumbOptions);

        return $this;
    }

    protected function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }

    protected function send404()
    {
        $meta = (object) array(
            'title'        => 'Страница не найдена',
            'description'  => 'Страница не найдена',
            'keywords'     => '',
        );

        $this->layout()->setVariable('meta', $meta);

        $response = $this->getResponse();
        $response->setStatusCode(404);
        $response->sendHeaders();
        return '';
    }
}