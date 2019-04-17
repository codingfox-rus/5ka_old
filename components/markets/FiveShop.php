<?php
namespace app\components\markets;

use Yii;
use app\models\Discount;

class FiveShop implements \app\interfaces\iMarket
{
    const SITE_URL = 'https://5ka.ru';
    const API_URL = '/api/special_offers/?format=json&ordering=-discount_percent';
    const PREVIEWS_PATH = '/previews/five_shop/';

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return Yii::$app->basePath . '/data/discount/five-shop.json';
    }

    /**
     * @param int $recordsPerPage
     * @return bool|string
     */
    public function getData($recordsPerPage = 1000)
    {
        $url = implode('', [
            self::SITE_URL,
            self::API_URL,
            '&records_per_page='. $recordsPerPage,
        ]);

        return file_get_contents($url);
    }

    /**
     * @return bool
     */
    public function updateData(): bool
    {
        $preparedData = $this->getPreparedData();

        $lastRow = Discount::find()
            ->select(['dateStart'])
            ->where([
                'market' => Discount::FIVE_SHOP,
            ])
            ->orderBy([
                'dateStart' => SORT_DESC,
            ])
            ->limit(1)
            ->asArray()
            ->one();

        $actualData = [];

        foreach ($preparedData as $item) {

            if (!$lastRow || (int)$item['dateStart'] > (int)$lastRow['dateStart']) {

                $actualData[] = $item;
            }
        }

        // Сохраняем актуальные данные
        $transaction = Yii::$app->db->beginTransaction();

        try {

            Yii::$app->db->createCommand()
                ->batchInsert(
                    Discount::tableName(),
                    Discount::getDataColumns(),
                    $actualData
                )
                ->execute();

            $transaction->commit();

            $totalRows = \count($actualData);

            echo "{$totalRows} added". PHP_EOL;

        } catch (\Exception $e) {

            $errMes = $e->getMessage();

            echo $errMes. PHP_EOL;

            Yii::error($errMes);

            $transaction->rollBack();
        }

        return true;
    }

    /**
     * @return array
     */
    public function getPreparedData(): array
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
    public function getItem(array $result): array
    {
        $item['market'] = Discount::FIVE_SHOP;

        $item['productId'] = null;

        $item['productName'] = $result['name'];

        $item['url'] = null;

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

    /**
     *
     */
    public function archiveData()
    {
        $res = Discount::updateAll(
            [
                'status' => 2
            ],
            'market = :market and status = 1 and dateEnd < :now',
            [
                ':market' => Discount::FIVE_SHOP,
                ':now' => time(),
            ]
        );

        echo "{$res} rows updated". PHP_EOL;
    }

    public function downloadImages()
    {
        $discounts = Discount::find()
            ->market(Discount::FIVE_SHOP)
            ->active()
            ->noPreview()
            ->limit(self::DOWNLOAD_LIMIT)
            ->all();

        foreach ($discounts as $discount) {

            $previewFile = uniqid(Discount::FIVE_SHOP, false) . '.jpg';

            $smallUrl = self::SITE_URL . $discount->imageSmall;
            $bigUrl = self::SITE_URL . $discount->imageBig;

            $smallPath = self::PREVIEWS_PATH . 'small/' . $previewFile;
            $bigPath = self::PREVIEWS_PATH . 'big/' . $previewFile;

            if (copy($smallUrl, Yii::$app->basePath .'/web'. $smallPath)) {

                $discount->previewSmall = $smallPath;

                echo Discount::FIVE_SHOP .' small preview copied successfully' . PHP_EOL;
            }

            if (copy($bigUrl, Yii::$app->basePath .'/web'. $bigPath)) {

                $discount->previewBig = $bigPath;

                echo Discount::FIVE_SHOP .' big preview copied successfully' . PHP_EOL;
            }

            $discount->save(false);
        }
    }
}