<?php
namespace Transports\Controller;

use Aptero\Mvc\Controller\AbstractActionController;
use Transports\Form\CommonForm;
use Transports\Model\Transport;

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

        switch ($transport->get('type')) {
            case Transport::TYPE_AUTO:
                $headerBg = '/images/headers/cars.jpg';
                break;
            case Transport::TYPE_BOAT:
                $headerBg = '/images/headers/boats.jpg';
                break;
            case Transport::TYPE_COPTER:
                $headerBg = '/images/headers/copter.jpg';
                break;
        }

        return $view->setVariables([
            'headerBg'       => $headerBg,
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