<?php
namespace Museums\Controller;

use Aptero\Mvc\Controller\AbstractActionController;

use Museums\Model\Museum;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MuseumsController extends AbstractActionController
{
    public function indexAction()
    {
        $view = $this->generate();
        $museums = $this->getMuseumsService()->getMuseums();

        return $view->setVariables([
            'museums'  => $museums,
        ]);
    }

    public function museumAction()
    {
        $view = $this->generate('/museums/');

        $url = $this->params()->fromRoute('url');

        $museum = $this->getMuseumsService()->getMuseum(['url' => $url]);

        if(!$museum->load()) {
            return $this->send404();
        }

        $this->addBreadcrumbs([['url' => $museum->getUrl(), 'name' => $museum->get('name')]]);

        return $view->setVariables([
            'header'        => $museum->get('header'),
            'museum'        => $museum,
            'breadcrumbs'   => $this->getBreadcrumbs(),
        ]);
    }

    public function pointAction()
    {
        $view = $this->generate('/museums/');

        $url = $this->params()->fromRoute('url');

        $point = $this->getMuseumsService()->getPoint(['url' => $url]);

        if(!$point->load()) {
            return $this->send404();
        }

        $this->addBreadcrumbs([['url' => $point->getUrl(), 'name' => $point->get('name')]]);

        return $view->setVariables([
            'header'        => $point->get('header'),
            'point'         => $point,
            'breadcrumbs'   => $this->getBreadcrumbs(),
        ]);
    }

    public function getMapPointsAction()
    {
        $id = $this->params()->fromPost();

        $museum = new Museum();
        $museum->setId($id);

        $points = [];

        foreach (Museum::getEntityCollection() as $museum) {
            $html =
                '<div class="museum-map-info">'.
                    '<div class="col-left">'.
                        '<img src="' . $museum->getPlugin('image')->getImage('s') . '">'.
                    '</div>'.
                    '<div class="col-right">'.
                        '<div class="title">' . str_replace('"', '', $museum->get('name')) . '</div>'.
                        '<div class="desc">' . str_replace('"', '', $museum->get('preview')) . '</div>'.
                        '<div class="btns"><a class="btn yellow" href="' . $museum->getUrl() . '">Подробнее</a></div>'.
                    '</div>'.
                '</div>';

            $points[] = [
                'lat'       => $museum->get('lat'),
                'lng'       => $museum->get('lng'),
                'name'      => $museum->get('name'),
                'preview'   => $museum->get('preview'),
                'type'      => 'museum',
                'html'      => $html,
            ];
        }

        return new JsonModel($points);
    }

    /**
     * @return \Museums\Service\MuseumsService
     */
    public function getMuseumsService()
    {
        return $this->getServiceLocator()->get('Museums\Service\MuseumsService');
    }
}