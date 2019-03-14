<?php
namespace app\components\markets;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Discount;

class Bristol implements \app\interfaces\iMarket
{
    const SITE_URL = 'https://bristol.ru';
    const API_URL = '/api/v1/menu';

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return Yii::$app->basePath .'/data/discount/bristol.json';
    }

    /**
     * @return bool|string
     */
    public function getData()
    {
        $url = implode('', [
            self::SITE_URL,
            self::API_URL
        ]);

        return file_get_contents($url);
    }

    public function updateData()
    {

    }

    /**
     * @return array
     */
    public function getPreparedData()
    {
        $filePath = $this->getFilePath();

        $data = json_decode(file_get_contents($filePath), true);

        if (isset($data['data'])) {

            $preparedData = [];

            foreach ($data['data'] as $dataItem) {

                foreach ($dataItem as $item) {

                    if (\is_array($item)) {

                        foreach ($item as $value) {

                            if (isset($value['product'])) {

                                $preparedData[] = $this->getItem($value['product']);
                            }
                        }
                    }
                }
            }

            return $preparedData;
        }

        return null;
    }

    /**
     * todo
     * @param array $cItem
     * @return mixed
     */
    public function getItem(array $cItem)
    {
        return $cItem;
    }

    public function archiveData()
    {
        // TODO: Implement archiveData() method.
    }

    public function downloadImages()
    {
        // TODO: Implement downloadImages() method.
    }
}