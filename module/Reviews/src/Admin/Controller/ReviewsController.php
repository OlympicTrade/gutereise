<?php
namespace ReviewsAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;

use ReviewsAdmin\Model\Review;
use Zend\View\Model\JsonModel;

use Aptero\Service\Admin\TableService;

class ReviewsController extends AbstractActionController
{
    public function __construct() {
        parent::__construct();

        $this->setFields(array(
            'name' => array(
                'name'      => 'Имя',
                'type'      => TableService::FIELD_TYPE_LINK,
                'field'     => 'name',
                'width'     => '15',
            ),
            'review' => array(
                'name'      => 'Комментарий',
                'type'      => TableService::FIELD_TYPE_TEXT,
                'field'     => 'review',
                'width'     => '75',
            ),
            'status' => array(
                'name'      => 'Статус',
                'type'      => TableService::FIELD_TYPE_TEXT,
                'field'     => 'status',
                'filter'    => function($value){
                    return '<i class="fa ' . ($value == Review::STATUS_VERIFIED ? 'fa-eye' : 'fa-eye-slash') . '"></i>';
                },
                'width'     => '10',
                'tdStyle'   => [
                    'text-align' => 'center'
                ],
                'thStyle'   => [
                    'text-align' => 'center'
                ]
            ),
        ));
    }
}