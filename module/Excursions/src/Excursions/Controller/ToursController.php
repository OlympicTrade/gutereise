<?php
namespace Excursions\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Excursions\Form\CommonForm;
use Excursions\Form\OrderForm;
use Excursions\Model\Excursion;
use Excursions\Model\Tags;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ToursController extends AbstractActionController
{
    public function indexAction()
    {
        $url = $this->params()->fromRoute('url');

        $toursService = $this->getToursService();

        if(!$url) {
            return $this->toursAction();
        }

        $tour = $toursService->getTour(['url' => $url])->load();

        if($tour) {
            return $this->tourAction($tour);
        }

        return $this->send404();
    }

    public function tagsAction()
    {
        $url = $this->params()->fromRoute('tag');

        $tag = new Tags();
        $tag->select()->where(['url' => $url]);

        return $this->toursAction(['tag' => $tag]);
    }

    public function toursAction($options = [])
    {
        $toursService = $this->getToursService();

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

        $tours = $toursService->getPaginator($page, $filters);

        if($this->getRequest()->isXmlHttpRequest()) {
            $resp = [];

            $resp['html']['items'] = $this->viewHelper('toursList', $tours);
            $resp['meta'] = $meta;

            return new JsonModel($resp);
        }

        if($tag) {
            $url = $tag->getUrl();
        } else {
            $url = '/tours/';
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('tours/tours/index');

        if($tag) {
            $this->addBreadcrumbs([['url' => $url, 'name' => $tag->get('name')]]);
            $view->setVariables([
                'header'      => $tag->get('name'),
            ]);
        }

        $filters['tag'] = $tag;

        return $view->setVariables([
            'filters'     => $filters,
            'tours'  => $tours,
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

    public function tourAction($tour)
    {
        $view = $this->generate('/tours/');
        $view->setTemplate('tours/tours/tour');

        $this->addBreadcrumbs([['url' => $tour->getUrl(), 'name' => $tour->get('name')]]);

        $meta = $this->layout()->getVariable('meta');
        $meta->title = $tour->get('title');
        $meta->description = $tour->get('description');

        $this->layout()->setVariable('meta', $meta);

        return $view->setVariables([
            'headerImage'    => $tour->getPlugin('background')->getImage('h'),
            'header'         => $tour->get('header'),
            'headerDesc'     => $tour->get('header_desc'),
            'tour'      => $tour,
            'commonForm'     => new CommonForm($tour),
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
            return new JsonModel(['data' => $this->getToursService()->getPrice($excursion, $form->getData())]);
        } else {
            return $jsonModel = new JsonModel(['errors' => $form->getMessages()]);
        }
    }

    /*public function orderAction()
    {
        $id = $this->params()->fromQuery('id');
        $tour = (new Tour(['id' => $id]))->load();

        if(!$tour) {
            return $this->send404();
        }

        $form = new OrderForm($tour);

        if($this->getRequest()->isPost()) {
            $form->setFilters();
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                return $jsonModel = new JsonModel(['errors' => []]);
                $formData = $form->getData();

                if($errors = $this->getToursService()->getPrice($tour, $formData)['errors']) {
                    return $jsonModel = new JsonModel([
                        'errors' => ['form' => $errors]
                    ]);
                }

                die('zxczxc');
                $this->getToursService()->addOrder($formData);
                return $jsonModel = new JsonModel([
                    'errors' => $form->getMessages()
                ]);
            } else {
                return $jsonModel = new JsonModel([
                    'errors' =>
                        $form->getMessages() + ['form' => $this->getToursService()->getPrice($tour, $form->getData())['errors']]
                ]);
            }
        }

        //$view = $this->generate('/tours/');
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables([
            'tour' => $tour,
            'form'      => $form,
        ]);

        return $view;
    }*/

    /**
     * @return \Excursions\Service\ToursService
     */
    public function getToursService()
    {
        return $this->getServiceLocator()->get('Excursions\Service\ToursService');
    }
}