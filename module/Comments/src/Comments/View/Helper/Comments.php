<?php
namespace Comments\View\Helper;

use Comments\Model\Comment;
use Translator\Model\Translator;
use Zend\View\Helper\AbstractHelper;

class Comments extends AbstractHelper
{
    public function __invoke($options)
    {
        if($options instanceof Comment) {
            return $this->comment($options);
        }

        $options = $options + [
            'header' => 'Вопросы и отзывы',
            'btn'    => 'Задать вопрос',
        ];

        $view = $this->getView();

        $comments = Comment::getEntityCollection();
        $comments->select()->where([
            'depend_id'     => $options['depend_id'],
            'depend_type'   => $options['depend_type'],
            'status'        => 1,
            'lang_code'     => Translator::getInstance()->getLangCode(),
        ])->order('time_create DESC');

        $html =
            '<div class="comments" data-type="' . $options['depend_type'] . '">'.
                '<div class="std-header">'.
                    '<h2>' . $view->tr($options['header']) . '</h2>'.
                    '<div class="desc">Задайте вопрос службе поддержки или найдите ответ в <a class="popup" href="/faq/">часто задаваемых вопросах</a></div>'.
                    //'<div class="separ"></div>'.
                '</div>'.
                '<div class="add-question">'.
                    '<a href="/comments/add/?depend=' . $options['depend_id'] . '&type=' . $options['depend_type'] . '" class="popup btn c2">' . $view->tr($options['btn']) . '</a>'.
                '</div>'.
                '<div class="list">';

        if(!$comments->count()) {
            $html .=
                '<div class="empty-list">' . $view->tr('Пока никто не оставил отзыв') . '</div>';
        }

        foreach ($comments as $comment) {
            $html .= $this->comment($comment);
        }

        $html .=
                '</div>'.
            '</div>';

        return $html;
    }

    public function comment($comment)
    {
        $view = $this->getView();

        $html =
            '<div class="comment">'.
                '<div class="info">'.
                    '<div class="name">' . $comment->get('name') . '</div>'.
                    '<div class="date">' . $view->date($comment->get('time_create')) . '</div>'.
                '</div>'.
                '<div class="question">' . $view->tr($comment->get('question')) . '</div>';

        if($comment->get('answer')) {
            $html .=
                '<div class="answer">'.
                    '<div class="admin">Gute Reise:</div>'.
                    $view->tr($comment->get('answer')) .
                '</div>';
        }

        $html .=
            '</div>';

        return $html;
    }
}