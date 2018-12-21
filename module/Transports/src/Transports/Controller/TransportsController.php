<?php
namespace Transports\Controller;

use Aptero\Mvc\Controller\AbstractActionController;
use Transports\Form\CommonForm;

class TransportsController extends AbstractActionController
{
    public function indexAction()
    {
        $view = $this->generate();

        $transports = $this->getTransportsService()->getTransports();

        return $view->setVariables([
            'transports'  => $transports,
        ]);
    }

    public function transportAction()
    {
        $url = $this->params()->fromRoute('url');
        $transport = $this->getTransportsService()->getTransport(['url' => $url]);

        /*$view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables([
            'transport' => $transport
        ]);
        return $view;*/


        $view = $this->generate('/transport/');


        $this->addBreadcrumbs([['url' => $transport->getUrl(), 'name' => $transport->get('name')]]);

        return $view->setVariables([
            'header'         => $transport->get('name'),
            'transport'      => $transport,
            'breadcrumbs'    => $this->getBreadcrumbs(),
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