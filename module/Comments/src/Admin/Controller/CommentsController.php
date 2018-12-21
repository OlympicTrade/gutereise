<?php
namespace CommentsAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;
use Aptero\Service\Admin\TableService;

class CommentsController extends AbstractActionController
{
    public function __construct()
    {
        $this->fields = [
            'name' => [
                'name'  => 'Название',
                'type'  => TableService::FIELD_TYPE_LINK,
                'field' => 'name',
                'width' => '15',
            ],
            'contact' => [
                'name'  => 'Контакты',
                'type'  => TableService::FIELD_TYPE_LINK,
                'field' => 'contact',
                'width' => '15',
            ],
            'question'  => [
                'name'  => 'Вопрос',
                'type'  => TableService::FIELD_TYPE_LINK,
                'field' => 'question',
                'width' => '70',
            ],
        ];

        return parent::__construct();
    }
}