<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Header extends AbstractHelper
{
    public function __invoke($options = [])
    {
        if($this->getView()->isMobile()) {
            return $this->mobile($options);
        } else {
            return $this->desktop($options);
        }
    }

    public function mobile($options = [])
    {
        $view = $this->getView();

        if(!isset($options['header'])) {
            $options['header'] = $view->header;
        }

        $html =
            '<h1 class="block">' . $options['header'] . '</h1>';

        return $html;
    }

    public function desktop($options = [])
    {
        $view = $this->getView();

        if(!isset($options['header'])) {
            $options['header'] = $view->header;
        }

        if(!isset($options['headerDesc'])) {
            $options['headerDesc'] = $view->headerDesc;
        }

        $html =
            '<div class="block header-block"' .
                //($options['background'] ? ' style="background-image: url(' . $options['background'] . ')"' : '') .
            '>';

        if($options['background']) {
            //$html .= '<div class="bg"><img src="' . $options['background'] . '" alt="' . $options['header'] . '"></div>';
            $html .= '<div class="bg" style="height: 400px"></div>';
        }

        if($options['wrapper']) {
            $html .= '<div class="wrapper">';
        }

        $html .= '<div class="box">';

        if($options['header']) {
            $html .= '<h1>' . $view->tr($options['header']) . '</h1>';
        }

        /*if($options['headerDesc']) {
            $html .= '<div class="desc">' . $options['headerDesc'] . '</div>';
        }*/

        $html .= $view->breadcrumbs($options['breadcrumbs'], ['delimiter' => ' / ', 'lastItem' => 'span', 'class' => 'breadcrumbs']);

        $html .= '</div>';

        if($options['wrapper']) {
            $html .= '</div>';
        }

        $html .=
            '<script>
                $(".bg").parallax({imageSrc: "' . $options['background'] . '"});
            </script>';

        $html .=
            '</div>';

        return $html;
    }
}