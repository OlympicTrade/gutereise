<?php

namespace Comments\Service;

use Application\Model\Settings;
use Aptero\Mail\Mail;
use Aptero\Service\AbstractService;
use Comments\Model\Comment;

class CommentsService extends AbstractService
{
    public function addComment($data)
    {
        $comment = new Comment();
        $comment->setVariables($data + ['time_create']);
        $comment->save();

        /*$sms = $this->getServiceManager()->get('Sms');
        $sms->send(
            Settings::getInstance()->get('admin_phone'),
            'Новый вопрос на сайте'
        );*/

        $mail = new Mail();

        $mail->setTemplate(MODULE_DIR . '/Comments/view/comments/mail/notice-admin.phtml')
            ->setHeader('Регистрация на сайте')
            ->addTo(Settings::getInstance()->get('admin_email'))
            ->setVariables([
                'comment'    => $comment,
            ])
            ->send();

        return $comment;
    }
}