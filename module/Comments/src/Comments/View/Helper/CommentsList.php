<?php
namespace Comments\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CommentsList extends AbstractHelper
{
    public function __invoke($comments)
    {
        $view = $this->getView();

        $html =
           '<div class="comments-list">';

        foreach($comments as $comment) {
            $html .=
                '<a href="' . $comment->getUrl() . '" class="brand">'
                    .'<div class="title">' . $comment->get('name') . '</div>'
                .'</a>';
        }

        $html .=
                '<div class="clear"></div>'
            .'</div>';

        return $html;
    }
}