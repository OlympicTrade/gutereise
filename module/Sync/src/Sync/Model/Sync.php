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
        $url = self::DB_DOMAIN . '/sync/' . $urlFunc . '/?' . http_build_query($params);
        $resp = (new \GuzzleHttp\Client())->request('GET', $url);

        try {
            $data = Json::decode($resp->getBody());
        } catch (\Exception $e) {
            var_dump($params);
            echo $e->getMessage();
            die();
        }

        return $data;
    }
}