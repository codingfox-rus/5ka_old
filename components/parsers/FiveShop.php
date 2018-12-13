<?php
namespace app\components\parsers;

use Yii;

class FiveShop
{
    const API_URL = 'https://5ka.ru/api/special_offers/?format=json&ordering=-discount_percent';

    public function getData($recordsPerPage = null)
    {
        $url = self::API_URL;

        if ($recordsPerPage) {

            $url .= '&records_per_page='. $recordsPerPage;
        }

        $json = file_get_contents($url);

        return json_decode($json, true);
    }
}