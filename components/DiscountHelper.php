<?php
namespace app\components;

use Yii;

class DiscountHelper
{
    /**
     * @param array $result
     * @return array
     */
    public function getItemData(array $result) : array
    {
        $item['itemId'] = $result['id'];

        $item['name'] = $result['name'];

        $item['description'] = $result['description'];

        $item['imageSmall'] = $result['image_small'];

        $item['imageBig'] = $result['image_big'];

        $item['paramId'] = $result['params']['id'];

        $item['specialPrice'] = $result['params']['special_price'];

        $item['regularPrice'] = $result['params']['regular_price'];

        $item['discountPercent'] = $result['params']['discount_percent'];

        $item['dateStart'] = $result['params']['date_start'];

        $item['dateEnd'] = $result['params']['date_end'];

        return $item;
    }
}