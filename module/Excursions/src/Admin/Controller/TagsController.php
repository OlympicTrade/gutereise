<?php
namespace ExcursionsAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;
use Aptero\Service\Admin\TableService;
use ExcursionsAdmin\Model\Tags;

class TagsController extends AbstractActionController
{
    public function __construct()
    {
        parent::__construct();

        $this->setFields([
            'name' => [
                'name' => 'Название',
                'type' => TableService::FIELD_TYPE_LINK,
                'width' => '30',
            ],
            'type' => [
                'name' => 'Тип',
                'type' => TableService::FIELD_TYPE_TEXT,
                'filter' => function ($value, $row) {
                    return [Tags::TYPE_MAIN => 'Главный', Tags::TYPE_TAG => 'Поисковый'][$value];
                },
                'width' => '70',
            ],
        ]);
    }
}