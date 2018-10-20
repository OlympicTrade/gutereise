<?php
namespace ApplicationAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->redirect()->toUrl('/admin/application/settings/');
    }
}