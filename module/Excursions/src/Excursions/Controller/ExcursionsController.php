<?php
namespace Excursions\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Excursions\Form\CommonForm;
use Excursions\Form\OrderForm;
use Excursions\Model\Excursion;
use Excursions\Model\Tags;
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

        return $this->send404();
    }

    public function tagsAction()
    {
        $url = $this->params()->fromRoute('tag');

        $tag = new Tags();
        $tag->select()->where(['url' => $url]);

        return $this->excursionsAction(['tag' => $tag]);
    }

    public function excursionsAction($options = [])
    {
        $excursionsService = $this->getExcursionsService();

        $tag  = $options['tag'] ?? null;

        $view = $this->generate();
        $meta = $this->layout()->getVariable('meta');

        if($tag) {
            $meta->title = $tag->get('title');
            $meta->description = $tag->get('description');

            $this->layout()->setVariable('meta', $meta);
        }

        $page = $this->params()->fromQuery('page', 1);

        $filters = $this->params()->fromQuery();

        if($tag) {
            $filters['tag'] = $tag->getId();
        }

        $excursions = $excursionsService->getPaginator($page, $filters);

        if($this->getRequest()->isXmlHttpRequest()) {
            $resp = [];

            $resp['html']['items'] = $this->viewHelper('excursionsList', $excursions);
            $resp['meta'] = $meta;

            return new JsonModel($resp);
        }

        if($tag) {
            $url = $tag->getUrl();
        } else {
            $url = '/excursions/';
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('excursions/excursions/index');

        if($tag) {
            $this->addBreadcrumbs([['url' => $url, 'name' => $tag->get('name')]]);
            $view->setVariables([
                'header'      => $tag->get('name'),
            ]);
        }

        $filters['tag'] = $tag;

        return $view->setVariables([
            'filters'     => $filters,
            'excursions'  => $excursions,
            'page'        => $page,
        ]);
    }

    public function productsFilters($options)
    {
        $filters = $this->params()->fromQuery();

        if($options['type']) {
            $filters['type'] = $options['type']->getId();
        }

        return $filters;
    }

    public function excursionAction($excursion)
    {
        $view = $this->generate('/excursions/');
        $view->setTemplate('excursions/excursions/excursion');

        $this->addBreadcrumbs([['url' => $excursion->getUrl(), 'name' => $excursion->get('name')]]);

        $meta = $this->layout()->getVariable('meta');
        $meta->title = $excursion->get('title');
        $meta->description = $excursion->get('description');

        $this->layout()->setVariable('meta', $meta);

        return $view->setVariables([
            'headerImage'    => $excursion->getPlugin('background')->getImage('h'),
            'header'         => $excursion->get('header'),
            'headerDesc'     => $excursion->get('header_desc'),
            'excursion'      => $excursion,
            'commonForm'     => new CommonForm($excursion),
        ]);
    }

    public function getPriceAction()
    {
        $exId = $this->params()->fromPost('id');
        $excursion = new Excursion();
        $excursion->setId($exId);

        if(!$excursion->load()) {
            return $this->send404();
        }

        $form = new CommonForm($excursion);
        $form->setFilters();
        $form->setData($this->params()->fromPost());

        if ($form->isValid()) {
            return new JsonModel(['data' => $this->getExcursionsService()->getPrice($excursion, $form->getData())]);
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

                if($errors = $this->getExcursionsService()->getPrice($excursion, $formData)['errors']) {
                    return $jsonModel = new JsonModel([
                        'errors' => ['form' => $errors]
                    ]);
                }

                die('zxczxc');
                $this->getExcursionsService()->addOrder($formData);
                return $jsonModel = new JsonModel([
                    'errors' => $form->getMessages()
                ]);
            } else {
                return $jsonModel = new JsonModel([
                    'errors' =>
                        $form->getMessages() + ['form' => $this->getExcursionsService()->getPrice($excursion, $form->getData())['errors']]
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