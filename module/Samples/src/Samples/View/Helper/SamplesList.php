<?php
namespace Samples\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SamplesList extends AbstractHelper
{
    public function __invoke($samples)
    {
        $view = $this->getView();

        $html =
           '<div class="samples-list">';

        foreach($samples as $sample) {
            $html .=
                '<a href="' . $sample->getUrl() . '" class="brand">'
                    .'<div class="title">' . $sample->get('name') . '</div>'
                .'</a>';
        }

        $html .=
                '<div class="clear"></div>'
            .'</div>';

        return $html;
    }
}