<?php
namespace Sync\Model;

use Zend\Json\Json;

class Sync
{
    public function getExcursionPrice($params)
    {
        return $this->load('get-price', $params);
    }

    public function getExcursionData($params)
    {
        return $this->load('get-excursion-data', $params);
    }

    public function getSettingsData($params = [])
    {
        return $this->load('get-settings-data', $params);
    }

    public function getTransportData($params)
    {
        return $this->load('get-transport-data', $params);
    }

    public function load($urlFunc, $params)
    {
        $url = SYNC_DOMAIN . '/sync/' . $urlFunc . '/?' . http_build_query($params);
        //dd($url);

        $resp = (new \GuzzleHttp\Client(['verify' => false]))->request('GET', $url);

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