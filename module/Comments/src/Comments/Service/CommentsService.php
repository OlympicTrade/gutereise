<?php

namespace Comments\Service;

use Translator\Model\Translator;
use Application\Model\Settings;
use Aptero\Mail\Mail;
use Aptero\Service\AbstractService;
use Comments\Model\Comment;

class CommentsService extends AbstractService
{
    public function addComment($data)
    {
        $contact = $data['contact'];
        if(!strpos($contact, '@')) {
            $contact = preg_replace('/[^0-9]/', '', $contact);
        }
        $data['contact'] = $contact;

        $comment = new Comment();
        $comment->setVariables($data + [
            'status'        => 1,
            'time_create'   => date('Y-m-d H:i:s'),
            'lang_code'     => Translator::getInstance()->getLangCode(),
        ]);
        $comment->save();

        $mail = new Mail();

        $mail->setTemplate(MODULE_DIR . '/Comments/view/comments/mail/notice-admin.phtml')
            ->setHeader('Новый вопрос на сайте')
            ->addTo(Settings::getInstance()->get('admin_email'))
            ->setVariables([
                'comment'  => $comment,
            ])
            ->send();

        return $comment;
    }
}