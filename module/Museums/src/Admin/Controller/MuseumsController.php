<?php
namespace MuseumsAdmin\Controller;

use Aptero\Mvc\Controller\Admin\AbstractActionController;
use Aptero\Service\Admin\TableService;

class MuseumsController extends AbstractActionController
{
    public function __construct() {
        parent::__construct();

        $this->setFields(array(
            'name' => array(
                'name'      => 'Название',
                'type'      => TableService::FIELD_TYPE_TEXT,
                'field'     => 'name',
                'width'     => '100',
                'hierarchy' => true,
            ),
        ));
    }
}