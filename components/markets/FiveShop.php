<?php
namespace app\components\markets;

use Yii;

class FiveShop implements \app\interfaces\iMarket
{
    const SITE_URL = 'https://5ka.ru';
    const API_URL = '/api/special_offers/?format=json&ordering=-discount_percent';

    public function getData($recordsPerPage = 1000)
    {
        $url = implode('', [
            self::SITE_URL,
            self::API_URL,
            '&records_per_page='. $recordsPerPage,
        ]);

        return file_get_contents($url);
    }

    public function loadData()
    {

    }
}