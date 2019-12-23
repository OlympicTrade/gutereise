<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ContentRender extends AbstractHelper
{
    public function __invoke($blocks, $options = [])
    {
        $html = '<div class="content-block">';

        foreach($blocks as $block) {
            $html .=
                 $this->header($block, $options).
                 $this->text($block, $options).
                 $this->images($block, $options);
        }

        $html .= '</div>';

        return $html;
    }

    protected function header($block, $options) {
        $attrs = $block->getPlugin('attrs');

        $html = '';

        if($header = $attrs->get('header')) {
            $html .=
                '<div class="std-header">'.
                    '<h2>' . $this->getView()->tr($header) . '</h2>'.
                    //'<div class="separ"></div>'.
                '</div>';
        }

        return $html;
    }

    protected function text($block, $options) {
        $html = '';

        $html .=
            '<div class="cb-text std-text">' . $block->get('text') . '</div>';

        return $html;
    }

    protected function images($block, $options) {
        if($block->getPlugin('attrs')->get('gallery_type') == 1) {
            return $this->imagesPhotosList($block, $options);
        } else {
            return $this->imagesSlider($block, $options);
        }
    }

    protected function imagesSlider($block, $options) {
        $html =
            '<div class="gallery">'.
                '<ul class="list">';

        $attrs = $block->getPlugin('attrs');

        for ($i = 1; $i < 3; $i++) {
            if($panoramaUrl = $attrs->get('panorama_' . $i)) {
                $html .=
                    '<li data-type="panorama" data-thumb="/images/panorama-thumb.jpg">'.
                        '<iframe src="' . $panoramaUrl . '" style="width: 100%; height: 500px" frameborder="0" allowfullscreen="true"></iframe>'.
                    '</li>';
            }
        }

        foreach ($block->getPlugin('images') as $image) {
            $alt = trim($options['alt-prefix'] . ' ' . $image->getDesc());

            $html .=
                '<li data-thumb="' . $image->getImage('hr') . '">'.
                    '<img src="' . $image->getImage(IS_MOBILE ? 'mb' : 'm') . '" alt="' . $alt . '"></li>'.
                '</li>';
        }

        $html .=
                '</ul>'.
            '</div>';

        return $html;
    }

    protected function imagesPhotosList($block, $options)
    {
        $html =
            '<div class="images-list">';

        foreach ($block->getPlugin('images') as $image) {
            $alt = trim($options['alt-prefix'] . ' ' . $image->getDesc());

            $imgSize = $image->getImageSize('p');

            $html .=
                '<div class="' . ($imgSize['width'] > $imgSize['height'] ? 'wide' : 'narrow') . '">'.
                    '<img src="' . $image->getImage(IS_MOBILE ? 'mb' : 'p') . '" alt="' . $alt . '">'.
                '</div>';
        }

        $html .=
            '</div>';

        return $html;
    }
}