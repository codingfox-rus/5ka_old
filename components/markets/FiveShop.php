<?php
namespace app\components\markets;

use Yii;
use app\models\Discount;

class FiveShop implements \app\interfaces\iMarket
{
    const API_URL = '/api/special_offers/?format=json&ordering=-discount_percent';

    /**
     * @return string
     */
    public function getFilePath()
    {
        return Yii::$app->basePath . '/data/discount/five-shop.json';
    }

    /**
     * @param int $recordsPerPage
     * @return bool|string
     */
    public function getData($recordsPerPage = 1000)
    {
        $siteUrl = Discount::getMarketUrls()[Discount::FIVE_SHOP];

        $url = implode('', [
            $siteUrl,
            self::API_URL,
            '&records_per_page='. $recordsPerPage,
        ]);

        return file_get_contents($url);
    }

    /**
     * @return array
     */
    public function getPreparedData() : array
    {
        $filePath = $this->getFilePath();

        $data = json_decode(file_get_contents($filePath), true);

        $preparedData = [];

        if (!empty($data['results'])) {

           foreach ($data['results'] as $result) {

               $preparedData[] = $this->getItem($result);
           }
        }

        return $preparedData;
    }

    /**
     * @param array $result
     * @return array
     */
    public function getItem(array $result) : array
    {
        $item['market'] = Discount::FIVE_SHOP;

        $item['productName'] = $result['name'];

        $item['description'] = $result['description'];

        $item['imageSmall'] = $result['image_small'];

        $item['imageBig'] = $result['image_big'];

        $item['regularPrice'] = $result['params']['regular_price'];

        $item['specialPrice'] = $result['params']['special_price'];

        $item['discountPercent'] = $result['params']['discount_percent'];

        $item['dateStart'] = $result['params']['date_start'];

        $item['dateEnd'] = $result['params']['date_end'];

        $item['status'] = Discount::STATUS_ACTIVE;

        //$item['jsonData'] = json_encode($result, JSON_UNESCAPED_UNICODE);

        $item['createdAt'] = time();

        return $item;
    }
}