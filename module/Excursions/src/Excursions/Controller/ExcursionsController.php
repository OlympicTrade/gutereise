<?php
namespace Excursions\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Excursions\Form\CommonForm;
use Excursions\Form\OrderForm;
use Excursions\Model\Excursion;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ExcursionsController extends AbstractActionController
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

        //$subUrl = substr($url, strrpos($url, '/') + 1);
        //$excursionUrl = substr($url, 0, strrpos($url, '/'));

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
        $view->setTemplate('excursions/excursions/index');

        if($type) {
            $this->addBreadcrumbs([['url' => $url, 'name' => $type->get('name')]]);
            $view->setVariables([
                'header'      => $type->get('name'),
                'breadcrumbs' => $this->getBreadcrumbs(),
            ]);
        }

        $filters['type'] = $type;

        $excursions = $this->getExcursionsService()->getExcursions();

        return $view->setVariables([
            'filters'     => $filters,
            'excursions'  => $excursions,
            'page'        => $page,
        ]);
    }

    public function productsFilters($options)
    {
        $filters = $this->params()->fromQuery();

        /*if(!isset($filters['sort'])) {
            $filters['sort'] = 'popularity';
        }*/

        if($options['type']) {
            $filters['type'] = $options['type']->getId();
        }

        return $filters;
    }

    public function excursionAction($excursion)
    {
        //

        //$url = $this->params()->fromRoute('url');

        //$excursion = $this->getExcursionsService()->getExcursion(['url' => $url]);
        $view = $this->generate('/excursions/');
        $view->setTemplate('excursions/excursions/excursion');

        $this->addBreadcrumbs([['url' => $excursion->getUrl(), 'name' => $excursion->get('name')]]);

        return $view->setVariables([
            'header'         => $excursion->get('header'),
            'headerDesc'     => $excursion->get('header_desc'),
            'excursion'      => $excursion,
            'breadcrumbs'    => $this->getBreadcrumbs(),
            'commonForm'     => new CommonForm(),
        ]);
    }

    public function getPriceAction()
    {
        $form = new CommonForm();
        $form->setFilters();
        $form->setData($this->params()->fromPost());

        if ($form->isValid()) {
            return new JsonModel(['data' => $this->getExcursionsService()->getPrice($form->getData())]);
        } else {
            return $jsonModel = new JsonModel(['errors' => $form->getMessages()]);
        }
    }

    public function orderAction()
    {
        $id = $this->params()->fromQuery('id');
        $excursion = (new Excursion(['id' => $id]))->load();

        if(!$excursion) {
            return $this->send404();
        }

        $form = new OrderForm($excursion);

        if($this->getRequest()->isPost()) {
            $form->setFilters();
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                return $jsonModel = new JsonModel(['errors' => []]);
                $formData = $form->getData();

                if($errors = $this->getExcursionsService()->getPrice($formData)['errors']) {
                    return $jsonModel = new JsonModel([
                        'errors' => ['form' => $errors]
                    ]);
                }

                $this->getExcursionsService()->addOrder($formData);
                return $jsonModel = new JsonModel([
                    'errors' => $form->getMessages()
                ]);
            } else {
                return $jsonModel = new JsonModel([
                    'errors' =>
                        $form->getMessages() + ['form' => $this->getExcursionsService()->getPrice($form->getData())['errors']]
                ]);
            }
        }

        //$view = $this->generate('/excursions/');
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables([
            'excursion' => $excursion,
            'form'      => $form,
        ]);

        return $view;
    }

    /**
     * @return \Excursions\Service\ExcursionsService
     */
    public function getExcursionsService()
    {
        return $this->getServiceLocator()->get('Excursions\Service\ExcursionsService');
    }
}