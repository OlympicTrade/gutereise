<?php
namespace Sync\Model;

use Zend\Json\Json;

class Sync
{
    const DB_DOMAIN = 'http://gutereise-db';

    public function getExcursionPrice($params)
    {
        return $this->load('get-price', $params);
    }

    public function getExcursionData($params)
    {
        return $this->load('get-excursion-data', $params);
    }

    public function load($urlFunc, $params)
    {
        $url = self::DB_DOMAIN . '/sync/' . $urlFunc . '/?' . \GuzzleHttp\Psr7\build_query($params);

        if($urlFunc == 'add-order') die($url);
        $json = file_get_contents($url);

        $data = Json::decode($json);

        return $data;
    }
}