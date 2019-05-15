<?php
namespace Museums\Controller;

use Aptero\Breadcrumbs\Breadcrumbs;
use Aptero\Mvc\Controller\AbstractActionController;

use Museums\Model\Museum;
use Museums\Model\Tags;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MuseumsController extends AbstractActionController
{
    public function indexAction()
    {
        $url = $this->params()->fromRoute('url');

        $museumsService = $this->getMuseumsService();

        if(!$url) {
            return $this->museumsAction();
        }

        $museum = $museumsService->getMuseum(['url' => $url])->load();

        if($museum) {
            return $this->museumAction($museum);
        }

        return $this->send404();
    }

    public function tagsAction()
    {
        $url = $this->params()->fromRoute('tag');

        $tag = new Tags();
        $tag->select()->where(['url' => $url]);

        return $this->museumsAction(['tag' => $tag]);
    }

    public function museumsAction($options = [])
    {
        $museumsService = $this->getMuseumsService();

        $tag  = $options['tag'] ?? null;

        $view = $this->generate('/attractions/');
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

        $museums = $museumsService->getPaginator($page, $filters);

        if($this->getRequest()->isXmlHttpRequest()) {
            $resp = [];

            $resp['html']['items'] = $this->viewHelper('museumsList', $museums);
            $resp['meta'] = $meta;

            return new JsonModel($resp);
        }

        if($tag) {
            $url = $tag->getUrl();
        } else {
            $url = '/museums/';
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('museums/museums/index');

        $headerBg = '/images/headers/petergof.jpg';

        if($tag) {
            $this->addBreadcrumbs([['url' => $url, 'name' => $tag->get('name')]]);
            $view->setVariables([
                'header'      => $tag->get('name'),
            ]);

            if($tag->getPlugin('background')->hasImage()) {
                $headerBg = $tag->getPlugin('background')->getImage('hr');
            }
        }

        $filters['tag'] = $tag;

        return $view->setVariables([
            'headerBg'    => $headerBg,
            'filters'     => $filters,
            'museums'     => $museums,
            'page'        => $page,
        ]);
    }

    public function museumAction()
    {
        $view = $this->generate('/attractions/');

        $url = $this->params()->fromRoute('url');

        $museum = $this->getMuseumsService()->getMuseum(['url' => $url]);

        if(!$museum->load()) {
            return $this->send404();
        }

        $view->setVariables([
            'museum'        => $museum,
            'header'        => $museum->get('header'),
        ]);

        if($this->isAjax()) {
            $view->setTemplate('museums/museums/museum-ajax');
            return $view;
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('museums/museums/museum');

        $this->addBreadcrumbs([['url' => $museum->getUrl(), 'name' => $museum->get('name')]]);

        return $view->setVariables([
            'headerImage'   => $museum->getPlugin('background')->getImage('h'),
            'headerDesc'    => $museum->get('header_desc'),
            'museum'        => $museum,
        ]);
    }

    /*public function attractionAction()
    {
        $view = $this->generate('/attractions/');

        $url = $this->params()->fromRoute('url');

        $attraction = $this->getMuseumsService()->getAttraction(['url' => $url]);

        if(!$attraction->load()) {
            return $this->send404();
        }

        $this->layout()->setVariable('canonical', $url);
        $view->setTemplate('museums/museums/attraction');

        $this->addBreadcrumbs([['url' => $attraction->getUrl(), 'name' => $attraction->get('name')]]);

        return $view->setVariables([
            'headerImage'   => $attraction->getPlugin('background')->getImage('h'),
            'header'        => $attraction->get('header'),
            'headerDesc'    => $attraction->get('header_desc'),
            'attraction'    => $attraction,
        ]);
    }*/

    public function getMapAttractionsAction()
    {
        $id = $this->params()->fromPost();

        $museum = new Museum();
        $museum->setId($id);

        $attractions = [];

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

            $attractions[] = [
                'lat'       => $museum->get('lat'),
                'lng'       => $museum->get('lng'),
                'name'      => $museum->get('name'),
                'preview'   => $museum->get('preview'),
                'type'      => 'museum',
                'html'      => $html,
            ];
        }

        return new JsonModel($attractions);
    }

    /**
     * @return \Museums\Service\MuseumsService
     */
    public function getMuseumsService()
    {
        return $this->getServiceLocator()->get('Museums\Service\MuseumsService');
    }
}