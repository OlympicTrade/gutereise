<?php
namespace Sync\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SyncController extends AbstractActionController
{
    public function syncRequestAction()
    {
        $type = $this->params()->fromQuery('type');
        $dbId = $this->params()->fromQuery('db_id');

        $this->getSyncService()->syncRequest($type, $dbId);

    }

    /**
     * @return \Sync\Service\SyncService
     */
    public function getSyncService()
    {
        return $this->getServiceLocator()->get('Sync\Service\SyncService');
    }
}