<?php
namespace Faq\Controller;

use Aptero\Mvc\Controller\AbstractActionController;
use Faq\Model\Faq;

class FaqController extends AbstractActionController
{
    public function indexAction()
    {
        $view = $this->generate();

        return $view->setVariables([
            'faq' => Faq::getEntityCollection()
        ]);
    }
}