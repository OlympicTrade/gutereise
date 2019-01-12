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

        $view = $this->generate('/transport/');

        $this->addBreadcrumbs([['url' => $transport->getUrl(), 'name' => $transport->get('name')]]);

        if($this->isAjax()) {
            $view->setTerminal(true);
            $view->setTemplate('transports/transports/transport-ajax.phtml');
        }

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