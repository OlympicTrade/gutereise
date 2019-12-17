<?php
namespace Hotels\Controller;

use Aptero\Mvc\Controller\AbstractMobileActionController;

use Hotels\Form\CommonForm;
use Zend\View\Model\JsonModel;

class MobileHotelsController extends AbstractMobileActionController
{
    public function indexAction()
    {
        $url = $this->params()->fromRoute('url');

        $hotelsService = $this->getHotelsService();

        if(!$url) {
            return $this->hotelsAction();
        }

        $hotel = $hotelsService->getHotel(['url' => $url])->load();

        if($hotel) {
            return $this->hotelAction($hotel);
        }

        $type = $hotelsService->getType(['url' => $url])->load();
        if($type) {
            return $this->hotelsAction(['type' => $type]);
        }

        return $this->send404();
    }

    public function hotelsAction($options = [])
    {
        $hotelsService = $this->getHotelsService();

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

        $hotels = $hotelsService->getPaginator($page, $filters);

        if($this->getRequest()->isXmlHttpRequest()) {
            $resp = [];

            $resp['html']['items'] = $this->viewHelper('hotelsList', $hotels);

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
            $url = '/hotels/';
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('hotels/mobile-hotels/index');

        if($type) {
            $this->addBreadcrumbs([['url' => $url, 'name' => $type->get('name')]]);
            $view->setVariables([
                'header'      => $type->get('name'),
                'breadcrumbs' => $this->getBreadcrumbs(),
            ]);
        }

        $filters['type'] = $type;

        //$hotels = $this->getHotelsService()->getHotels();

        return $view->setVariables([
            'filters'     => $filters,
            'hotels'  => $hotels,
            'page'        => $page,
        ]);
    }

    public function hotelAction($hotel)
    {
        $view = $this->generate('/hotels/');
        $view->setTemplate('hotels/mobile-hotels/hotel');

        $this->addBreadcrumbs([['url' => $hotel->getUrl(), 'name' => $hotel->get('name')]]);

        return $view->setVariables([
            'headerImage'    => $hotel->getPlugin('background')->getImage('h'),
            'header'         => $hotel->get('header'),
            'headerDesc'     => $hotel->get('header_desc'),
            'hotel'      => $hotel,
            'breadcrumbs'    => $this->getBreadcrumbs(),
            'commonForm'     => new CommonForm($hotel),
        ]);
    }

    /**
     * @return \Hotels\Service\HotelsService
     */
    public function getHotelsService()
    {
        return $this->getServiceLocator()->get('Hotels\Service\HotelsService');
    }
}