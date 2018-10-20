<?php
namespace Application\Controller;

use Application\Model\About;
use Aptero\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $view = $this->generate();

        $contacts = $this->layout()->getVariable('contacts');

        $excursions = $this->getExcursionsService()->getExcursions();

        return $view->setVariables([
            'contacts'      => $contacts,
            'excursions'    => $excursions,
            'page'          => $this->layout()->getVariable('page'),
        ]);
    }

    public function aboutAction()
    {
        $view = $this->generate();

        $about = new About();

        return $view->setVariables([
            'about' => $about,
        ]);
    }

    public function redirectAction()
    {
        $code =  $this->params()->fromQuery('code');

        $session = new Container();
        if(!$session->redirect) {
            return $this->send404();
        }

        $redirectUrl = $session->redirect;
        $redirectUrl .= (strpos($redirectUrl, '?') ? '' : '?') . 'code=' . $code;

        return $this->redirect()->toUrl($redirectUrl);
    }

    public function sitemapAction()
    {
        $sitemapXml = $this->getSitemapService()->generateSitemap();
        header('Content-type: application/xml');
		
		file_put_contents(PUBLIC_DIR . '/sitemap.xml', $sitemapXml);
        die($sitemapXml);
    }

    public function robotsAction()
    {
        $settings = $this->getServiceLocator()->get('Settings');
        header('Content-type: text/plain');
        die($settings->get('robots'));
    }

    public function pageAction()
    {
        $view = $this->generate();

        $layout = $this->layout();
        $page = $layout->getVariable('page');

        if(!$page) {
            return $this->send404();
        }

        if($page->get('redirect_url')) {
            return $this->redirect()->toUrl($page->get('redirect_url'));
        }

        if(!$page->getId()) {
            $response = $this->getResponse();
            $response->setStatusCode(404);
            $response->sendHeaders();
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            $view->setTemplate('application/index/page-ajax');
            $view->setTerminal(true);


            $view->setVariables(array(
                'header'  => $layout->getVariable('header'),
                'text'    => $layout->getVariable('page')->get('text'),
            ));
        }

        return $view;
    }

    /**
     * @return \Application\Service\SitemapService
     */
    protected function getSitemapService()
    {
        return $this->getServiceLocator()->get('Application\Service\SitemapService');
    }

    /**
     * @return \Excursions\Service\ExcursionsService
     */
    protected function getExcursionsService()
    {
        return $this->getServiceLocator()->get('Excursions\Service\ExcursionsService');
    }
}
