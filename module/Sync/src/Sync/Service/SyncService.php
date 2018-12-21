<?php

namespace Sync\Service;

use ApplicationAdmin\Model\Settings;
use Aptero\Service\AbstractService;
use ExcursionsAdmin\Model\Excursion;
use TransportsAdmin\Model\Transport;

class SyncService extends AbstractService
{
    public function syncRequest($type, $dbId)
    {
        if($type == 'settings') {
            Settings::getInstance()
                ->sync()
                ->save();
            die('Success');
        }

        switch ($type) {
            case 'excursion':
                $model = new Excursion();
                $model->select()->where([
                    'db_excursion_id' => $dbId
                ]);
                break;
            case 'transport':
                $model = new Transport();
                $model->select()->where([
                    'db_transport_id' => $dbId
                ]);
                break;
            default:
                die('Unknown type ' . $type);
        }

        if(!$model->load()) {
            die('Entity with ID ' . intval($dbId) . ' not found');
        }

        $model->save();

        die('Success');
    }
}