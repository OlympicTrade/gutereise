<?php

namespace Transports\Service;

use Aptero\Service\AbstractService;
use Transports\Model\Transport;

class TransportsService extends AbstractService
{
    public function getTransports($filters = [])
    {
        $transports = Transport::getEntityCollection();
        return $transports;
    }

    public function getTransport($filters = [])
    {
        $transport = new Transport();

        $transport->select()
            ->where(['url' => $filters['url']]);

        return $transport;
    }
}