<?php
namespace Hotels\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

class HotelsController extends AbstractActionController
{
    public function indexAction()
    {
        $view =  $this->generate();
        $hotels = $this->getHotelsService()->test();

        dd(count($hotels['result']));


        d($hotels['result'][0]['name']);
        dd($hotels['result'][0]['images'][0]['url']);
    }

    /**
     * @return \Hotels\Service\HotelsService
     */
    public function getHotelsService()
    {
        return $this->getServiceLocator()->get('Hotels\Service\HotelsService');
    }
}