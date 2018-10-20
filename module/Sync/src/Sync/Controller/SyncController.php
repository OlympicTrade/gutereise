<?php
namespace Sync\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SyncController extends AbstractActionController
{
    public function indexAction()
    {
        $this->generate();

        $sync = $this->getSyncService();

        return [
            'sync' => $sync
        ];
    }

    /**
     * @return \Sync\Service\SyncService
     */
    public function getSyncService()
    {
        return $this->getServiceLocator()->get('Sync\Service\SyncService');
    }
}