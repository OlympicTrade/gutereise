<?php
namespace FaqAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;

use Zend\View\Model\JsonModel;

use Aptero\Service\Admin\TableService;

class FaqController extends AbstractActionController
{
    protected $fields = [
        'question' => [
            'name'      => 'Вопрос',
            'type'      => TableService::FIELD_TYPE_TEXT,
            'field'     => 'question',
            'width'     => '50',
            'tdStyle'   => [
                'text-align' => 'left'
            ],
            'thStyle'   => [
                'text-align' => 'left'
            ],
        ],
        'answer' => [
            'name'      => 'Ответ',
            'type'      => TableService::FIELD_TYPE_TEXT,
            'field'     => 'question',
            'width'     => '50',
            'tdStyle'   => [
                'text-align' => 'left'
            ],
            'thStyle'   => [
                'text-align' => 'left'
            ],
        ],
    ];
}