<?php
namespace Transports\Controller;

use Aptero\Mvc\Controller\AbstractMobileActionController;
use Transports\Form\CommonForm;

class MobileTransportsController extends AbstractMobileActionController
{
    public function indexAction()
    {
        $view = $this->generate();

        $transports = $this->getTransportsService()->getTransports();

        $view->setTemplate('transports/mobile-transports/index');

        return $view->setVariables([
            'transports'  => $transports,
        ]);
    }

    public function transportAction()
    {
        $url = $this->params()->fromRoute('url');
        $transport = $this->getTransportsService()->getTransport(['url' => $url]);

        $view = $this->generate('/transport/');

        $this->addBreadcrumbs([['url' => $transport->getUrl(), 'name' => $transport->get('name')]]);

        return $view->setVariables([
            'header'         => $transport->get('name'),
            'transport'      => $transport,
            'commonForm'     => new CommonForm($transport),
        ]);
    }

    /**
     * @return \Transports\Service\TransportsService
     */
    public function getTransportsService()
    {
        return $this->getServiceLocator()->get('Transports\Service\TransportsService');
    }
}