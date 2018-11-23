<?php
namespace Comments\View\Helper;

use Comments\Model\Comment;
use Zend\View\Helper\AbstractHelper;

class Comments extends AbstractHelper
{
    public function __invoke($options)
    {
        if($options instanceof Comment) {
            return $this->comment($options);
        }

        $comments = Comment::getEntityCollection();
        $comments->select()->where([
            'depend_id'     => $options['depend_id'],
            'depend_type'   => $options['depend_type'],
        ])->order('time_create DESC');

        $html =
            '<div class="comments">'.
                '<div class="std-header">'.
                    '<h2>Вопросы и отзывы</h2>'.
                    '<div class="separ"></div>'.
                '</div>'.
                /*'<form action="/comments/add-comment/" class="form cols">'.
                    '<input type="hidden" name="depend_id" value="' . $options['depend_id'] . '">'.
                    '<input type="hidden" name="depend_type" value="' . $options['depend_type'] . '">'.
                    '<div class="row cols">'.
                        '<div class="col-50"><input class="std-input" name="name" placeholder="Имя"></div>'.
                        '<div class="col-50"><input class="std-input" name="contact" placeholder="Телефон или E-mail"></div>'.
                    '</div>'.
                    '<div class="row cols">'.
                        '<div class="col-100"><textarea class="std-textarea" name="question" placeholder="Вопрос или отзыв"></textarea></div>'.
                    '</div>'.
                    '<div class="row">'.
                        '<input type="submit" class="btn black" value="Отправить">'.
                        '<div class="notice">Мы отправим ответ на e-mail или по SMS на указанный телефон</div>'.
                    '</div>'.
                '</form>'.*/
                '<div class="list">';

        if(!$comments->count()) {
            $html .=
                '<div class="empty-list">Пока никто не оставил отзыв</div>';
        }

        foreach ($comments as $comment) {
            $html .= $this->comment($comment);
                /*'<div class="comment">'.
                    '<div class="info">'.
                        '<div class="name">' . $comment->get('name') . '</div>'.
                        '<div class="date">' . $view->date($comment->get('time_create')) . '</div>'.
                    '</div>'.
                    '<div class="question">' . $comment->get('question') . '</div>';

            if($comment->get('answer')) {
                $html .=
                    '<div class="answer">'.
                        '<div class="admin">Gute Reise:</div>'.
                        $comment->get('answer') .
                    '</div>';
            }

            $html .=
                '</div>';*/
        }

        $html .=
                '</div>'.
            '</div>';

        return $html;
    }

    public function comment($comment)
    {
        $html =
            '<div class="comment">'.
                '<div class="info">'.
                    '<div class="name">' . $comment->get('name') . '</div>'.
                    '<div class="date">' . $this->getView()->date($comment->get('time_create')) . '</div>'.
                '</div>'.
                '<div class="question">' . $comment->get('question') . '</div>';

        if($comment->get('answer')) {
            $html .=
                '<div class="answer">'.
                    '<div class="admin">Gute Reise:</div>'.
                    $comment->get('answer') .
                '</div>';
        }

        $html .=
            '</div>';

        return $html;
    }
}