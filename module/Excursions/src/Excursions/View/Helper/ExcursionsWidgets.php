<?php
namespace Excursions\View\Helper;

use Aptero\Db\Entity\EntityCollection;
use Aptero\Db\Entity\EntityHierarchy;
use Aptero\Form\Form;
use Zend\View\Helper\AbstractHelper;

class ExcursionsWidgets extends AbstractHelper
{
    public function __invoke($data, $widgets)
    {
        $html = '';

        $html .=
            '<div class="filters">' .
            '<input type="hidden" class="base-url" value="' . $data['url'] . '">';

        foreach ($widgets as $wName => $wData) {
            switch ($wName) {
                case 'search':
                    $html .= $this->renderSearch($wData);
                    break;
                case 'catalog':
                    $html .= $this->renderCatalog($wData);
                    break;
                case 'catalog2':
                    $html .= $this->renderCatalog2($wData);
                    break;
                default:
                    break;
            }
        }

        $html .= '</div>';

        return $html;
    }

    protected function renderSearch($data)
    {
        $val = $data['value'];

        $view = $this->getView();

        $html =
            '<div class="search">' .
            '<i class="fas fa-search"></i>' .
            '<input class="search-input" type="text" name="query" value="' . $val . '" placeholder="' . $view->tr('Поиск по названию') . '">' .
            '</div>';

        return $html;
    }

    /*public function renderCatalog2($items)
    {
        $view = $this->getView();

        $items->select()->where([
            'type'    => 2,
            'active'  => 1,
        ]);

        $html = '';

        foreach ($items as $item) {
            $html .=
                '<div class="row">'.
                    '<a href="' . $item->getUrl() .'">' . $view->tr($item->get('name')) . '</a>';

            $children = $item->getChildren();
            if($children->count()) {
                $html .=
                    '<div class="sub">'.
                        $this->renderCatalog2($children).
                    '</div>';
            }

            $html .=
                '</div>';
        }

        return $html;
    }*/

    public function renderCatalog($data)
    {
        $data = $data + [
            'limit'    => 9,
            'counter'  => true,
            'all'      => ['text' => '', 'url' => ''],
        ];

        $html = '';

        $view = $this->getView();

        $i = 0;
        $count = 0;
        foreach ($data['data'] as $item) {
            $i++; $count += $item->get('count');

            if($i == 10) {
                $html .= '<div class="h-box">';
            }

            $html .=
                '<div class="row">'.
                    '<a href="' . $item->getUrl() .'">' .
                        $view->tr($item->get('name')) .
                        ($data['counter'] ? ' <span>' . $item->get('count') . '</span>' : '').
                    '</a>';

            if($item instanceof EntityHierarchy) {
                $children = $item->getChildren();

                if($children->count()) {
                    $html .= $this->renderCatalogSub($children);
                }
            }

            $html .=
                '</div>';
        }

        if($data['limit'] && $i > $data['limit']) {
            $html .=
                '</div>'
                .'<span class="btn s show-all">' . $view->tr('Весь список') . '</span>';
        } elseif($i <= 1) {
            return '';
        }

        if($data['all']['text']) {
            $html =
                '<div class="row">' .
                    '<a href="/excursions/">Все экскурсии<span> ' . $count . '</span></a>' .
                '</div>' .
                $html;
        }

        return $html;
    }

    protected function renderCatalogSub($items)
    {
        $view = $this->getView();

        $html =
            '<div class="sub">';

        foreach ($items as $item) {
            $html .=
                '<div class="row">'.
                    '<a href="' . $item->getUrl() .'">' . $view->tr($item->get('name')) . '</a>'.
                '</div>';
        }

        $html .=
            '</div>';

        return $html;
    }
}