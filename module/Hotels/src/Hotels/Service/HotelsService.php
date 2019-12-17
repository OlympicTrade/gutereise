<?php

namespace Hotels\Service;

use Aptero\Service\AbstractService;
use Zend\Json\Json;
use GuzzleHttp\Client as Guzzle;

class HotelsService extends AbstractService
{
    const KEY  = '1633';
    const PASS = 'c0682d9b-aa20-4500-a9e2-c45105aad770';
    const TYPE = 'affiliate'; //b2b

    public function getRegions()
    {
        $reps = $this->send('/region/list/', [
            'limit' => 9999,
            'types' => 'City',
        ]);

        foreach($reps['result'] as $row) {
            if($row['name_ru'] == 'Санкт-Петербург') {
                dd($row);
            }
        }

        dd();
    }

    public function getRegionHotels()
    {
        $reps = $this->send('/region/hotel/list/', [
            'region_id' => 2042, //Petersburg
        ]);

        dd($reps['result']);
    }

    /*public function getHotels()
    {
        $reps = $this->send('/hotel/list/', [
            'ids'   => ['spbmohovaya_32_apart_hotel',],
            'lang'  => 'ru'
        ]);
        return $reps;
    }*/

    public function getHotels()
    {
        $resp = $this->send('/hotel/rates/', [
            'region_id' => 2042, //Petersburg
            //'ids'       => ["adagio_moscow_paveletskaya","citadines_kurfurstendamm_berlin"],
            'checkin'   => '2019-12-17',
            'checkout'  => '2019-12-18',
            'adults'    => 2,
            'children'  => [],
            'lang'      => 'ru',
            'currency'  => 'default',
        ]);

        return $resp;
    }

    public function test()
    {
        $resp = $this->send('/ordergroup/info/', [
            'pagination' => [
                'page_size'   => '2',
                'page_number' => '1',
            ],
            'ordering'   => [
                'ordering_type' => 'asc',
                'ordering_by'   => 'invoice_id',
            ],
        ], 3);
        dd($resp);
    }

    public function send($path, $params = [], $version = 2)
    {
        $url = 'https://api.worldota.net/api/' . self::TYPE . '/v' . $version . '/' . trim($path, '/');
        //$url = 'https://partner.ostrovok.ru/api/' . self::TYPE . '/v' . $version . '/' . trim($path, '/');

        $client = new Guzzle();
        $response = $client->post($url, [
            'auth' => [self::KEY, self::PASS],
            'json' => $params + [
                'format' => 'json',
                'lang' => 'ru',
            ],
        ]);

        return Json::decode($response->getBody()->getContents(), 1);
    }
}