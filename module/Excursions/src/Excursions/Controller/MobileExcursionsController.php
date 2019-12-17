<?php
namespace Excursions\Controller;

use Aptero\Mvc\Controller\AbstractMobileActionController;

use Excursions\Form\CommonForm;
use Zend\View\Model\JsonModel;

class MobileExcursionsController extends AbstractMobileActionController
{
    public function indexAction()
    {
        $url = $this->params()->fromRoute('url');

        $excursionsService = $this->getExcursionsService();

        if(!$url) {
            return $this->excursionsAction();
        }

        $excursion = $excursionsService->getExcursion(['url' => $url])->load();

        if($excursion) {
            return $this->excursionAction($excursion);
        }

        $type = $excursionsService->getType(['url' => $url])->load();
        if($type) {
            return $this->excursionsAction(['type' => $type]);
        }

        return $this->send404();
    }

    public function excursionsAction($options = [])
    {
        $excursionsService = $this->getExcursionsService();

        $type  = $options['type'] ?? null;

        $view = $this->generate();
        $meta = $this->layout()->getVariable('meta');

        if($type) {
            $meta->title = $type->get('title');
            $meta->description = $type->get('description');

            $this->layout()->setVariable('meta', $meta);
        }

        $page = $this->params()->fromQuery('page', 1);

        $filters = $this->params()->fromQuery();

        if($type) {
            $filters['type'] = $type->getId();
        }

        $excursions = $excursionsService->getPaginator($page, $filters);

        if($this->getRequest()->isXmlHttpRequest()) {
            $resp = [];

            $resp['html']['items'] = $this->viewHelper('excursionsList', $excursions);

            //$widgetsHelper = $this->getViewHelper('catalogWidgets');

            $resp['html']['filters'] =
                /*$widgetsHelper('price', ['data' => $filters['price'], 'min' => $priceMinMax['min'], 'max' => $priceMinMax['max']])
                .$widgetsHelper('sort', ['data' => $filters['sort']])
                .$widgetsHelper('brands', ['data' => $filters['brands'], 'brands' => $brands]);*/

            $resp['meta'] = $meta;

            return new JsonModel($resp);
        }

        if($type) {
            $url = $type->getUrl();
        } else {
            $url = '/excursions/';
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('excursions/mobile-excursions/index');

        if($type) {
            $this->addBreadcrumbs([['url' => $url, 'name' => $type->get('name')]]);
            $view->setVariables([
                'header'      => $type->get('name'),
                //'breadcrumbs' => $this->getBreadcrumbs(),
            ]);
        }

        $filters['type'] = $type;

        //$excursions = $this->getExcursionsService()->getExcursions();

        return $view->setVariables([
            'filters'     => $filters,
            'excursions'  => $excursions,
            'page'        => $page,
        ]);
    }

    public function excursionAction($excursion)
    {
        $view = $this->generate('/excursions/');
        $view->setTemplate('excursions/mobile-excursions/excursion');

        $this->addBreadcrumbs([['url' => $excursion->getUrl(), 'name' => $excursion->get('name')]]);

        return $view->setVariables([
            'headerImage'    => $excursion->getPlugin('background')->getImage('h'),
            'header'         => $excursion->get('header'),
            'headerDesc'     => $excursion->get('header_desc'),
            'excursion'      => $excursion,
            //'breadcrumbs'    => $this->getBreadcrumbs(),
            'commonForm'     => new CommonForm($excursion),
        ]);
    }

    /**
     * @return \Excursions\Service\ExcursionsService
     */
    public function getExcursionsService()
    {
        return $this->getServiceLocator()->get('Excursions\Service\ExcursionsService');
    }
}