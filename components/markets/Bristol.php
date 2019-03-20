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

    /**
     * @return bool
     */
    public function updateData(): bool
    {
        $preparedData = $this->getPreparedData();

        // Сохраняем актуальные данные
        $transaction = Yii::$app->db->beginTransaction();

        try {

            Yii::$app->db->createCommand()
                ->batchInsert(
                    Discount::tableName(),
                    Discount::getDataColumns(),
                    $preparedData
                )
                ->execute();

            $transaction->commit();

            $totalRows = \count($preparedData);

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
        $productArray = $this->getProductArray();
        $notDiscountProductIds = [];
        $discountProductIds = [];

        foreach ($productArray as $product) {

            if (isset($product['price_old'], $product['discount_percent'])) {
                $discountProductIds[] = $product['id'];
            } else {
                $notDiscountProductIds[] = $product['id'];
            }
        }

        // Вначале архивируем неактуальные скидки
        Discount::updateAll([
            'status' => Discount::STATUS_ARCHIVE,
        ], [
            'in', 'productId', $notDiscountProductIds
        ]);

        // Добавляем актуальные скидки
        $actualRows = Discount::find()
            ->select('productId')
            ->market(Discount::BRISTOL)
            ->active()
            ->asArray()
            ->all();

        $actualProductIds = array_map(function($row){
            return $row['productId'];
        }, $actualRows);

        $newProductIds = array_diff($discountProductIds, $actualProductIds);

        $preparedData = [];

        foreach ($productArray as $product) {

            if (\in_array($product['id'], $newProductIds, true)) {

                $preparedData[] = $this->getItem($product);
            }
        }

        return $preparedData;
    }

    /**
     * @return array
     */
    public function getProductArray(): array
    {
        $filePath = $this->getFilePath();

        $data = json_decode(file_get_contents($filePath), true);

        if (isset($data['data'])) {

            $productArray = [];

            foreach ($data['data'] as $dataItem) {

                foreach ($dataItem as $item) {

                    if (\is_array($item)) {

                        foreach ($item as $value) {

                            if (isset($value['product'])) {

                                $productArray[] = $value['product'];
                            }
                        }
                    }
                }
            }

            return $productArray;
        }

        return [];
    }

    /**
     * @param array $result
     * @return mixed
     */
    public function getItem(array $result)
    {
        $item['market'] = Discount::BRISTOL;

        $item['productId'] = $result['id'];

        $item['productName'] = $result['name'];

        $item['url'] = $result['url'];

        $item['description'] = null;

        $item['imageSmall'] = null;

        $item['imageBig'] = $result['picture'];

        $item['regularPrice'] = $result['price_old'];

        $item['specialPrice'] = $result['price'];

        $item['discountPercent'] = $result['discount_percent'];

        $item['dateStart'] = null;

        $item['dateEnd'] = null;

        $item['status'] = Discount::STATUS_ACTIVE;

        $item['createdAt'] = time();

        return $item;
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