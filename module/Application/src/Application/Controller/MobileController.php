<?php
namespace Application\Controller;

use Application\Model\About;
use Aptero\Mvc\Controller\AbstractMobileActionController;
use Zend\View\Model\ViewModel;

class MobileController extends AbstractMobileActionController
{
    public function indexAction()
    {
        $this->generate();

        $contacts = $this->layout()->getVariable('contacts');

        return [
            'contacts' => $contacts,
        ];
    }

    public function aboutAction()
    {
        $view = $this->generate();

        $about = new About();

        return $view->setVariables([
            'about' => $about,
        ]);
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
        $this->generate();

        $layout = $this->layout();

        $page = $layout->getVariable('page');

        if($page->get('redirect_url')) {
            return $this->redirect()->toUrl($page->get('redirect_url'));
        }

        if(!$page->getId()) {
            $response = $this->getResponse();
            $response->setStatusCode(404);
            $response->sendHeaders();
        }

        $view = new ViewModel();
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
}
