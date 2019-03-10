<?php
namespace app\components\markets;

use Yii;
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
    public function getPreparedData(): array
    {
        $filePath = $this->getFilePath();

        $data = json_decode(file_get_contents($filePath), true);

        $preparedData = [];

        foreach ($data as $item) {

            $preparedData[] = $this->getItem($item);
        }

        return $preparedData;
    }

    public function getItem(array $dataChunk): array
    {

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