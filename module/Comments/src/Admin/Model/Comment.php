<?php
namespace CommentsAdmin\Model;

use Application\Model\Settings;
use Aptero\Db\Entity\Entity;
use Aptero\Mail\Mail;
use Aptero\Sms\Sms;
use ExcursionsAdmin\Model\Excursion;

class Comment extends Entity
{
    const TYPE_EXCURSION = 1;
    const TYPE_TOUR      = 2;
    const TYPE_TRANSPORT = 3;

    public function __construct()
    {
        $this->setTable('comments');

        $this->addProperties([
            'name'         => [],
            'depend_id'    => [],
            'depend_type'  => [],
            'contact'      => [],
            'question'     => [],
            'answer'       => [],
            'send'         => ['default' => 1],
            'time_create'  => [],
        ]);

        $this->getEventManager()->attach(array(Entity::EVENT_PRE_INSERT, Entity::EVENT_PRE_UPDATE), function ($event) {
            $model = $event->getTarget();
            if(!$model->get('send') || !$model->get('answer')) {
                return true;
            }

            if(strpos($model->get('contact'), '@')) {
                $mail = new Mail();
                $mail->setTemplate(MODULE_DIR . '/Comments/view/comments/mail/notice-client.phtml')
                    ->setHeader('Регистрация на сайте')
                    ->addTo(Settings::getInstance()->get('admin_email'))
                    ->setVariables([
                        'comment' => $model,
                    ])
                    ->send();
            } else {
                $sms = new Sms();
                $sms->send(
                    Settings::getInstance()->get('admin_phone'),
                    'Gute Reise - ' . $model->get('name') . ': ' . $model->get('answer')
                );
            }

            return true;
        });
    }

    public function getParent()
    {
        switch ($this->get('depend_type')) {
            case self::TYPE_EXCURSION :
                $parent = new Excursion();
                break;
            /*case self::TYPE_TYPE :
                $parent = new Excursion();
                break;
            case self::TYPE_TRANSPORT :
                $parent = new Excursion();
                break;*/
            default:
                return false;
        }

        return $parent->setId($this->get('depend_id'))->load();
    }
}