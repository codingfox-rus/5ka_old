<?php
namespace app\components\markets;

use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ConnectException;
use app\models\Region;
use app\models\Location;
use app\models\Discount;
use app\models\Product;

class FiveShop implements \app\interfaces\iMarket
{
    public const SITE_URL           = 'https://5ka.ru';
    public const DOMAIN             = '.5ka.ru';

    public const REGIONS_API_URL    = '/api/regions/';
    public const DISCOUNT_API_URL   = '/api/special_offers/?format=json&ordering=-discount_percent';
    public const PREVIEWS_PATH      = '/previews/five_shop/';

    public const DEFAULT_LOCATION_ID    = 2223;
    public const REQUEST_TIMEOUT        = 30;
    public const DAY_TIME               = 86400;

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function updateRegions(): bool
    {
        $regionsUrl = self::SITE_URL . self::REGIONS_API_URL;

        $regionsData = json_decode(file_get_contents($regionsUrl), true);

        $regions = $regionsData['regions'] ?? null;

        if ($regions) {

            // Очищаем старые данные
            Region::deleteAll();

            $columns = array_keys((new Region())->attributes);

            $totalRegions = Yii::$app->db
                ->createCommand()
                ->batchInsert(
                    Region::tableName(),
                    $columns,
                    $regions
                )->execute();

            echo "Сохранено $totalRegions регионов". PHP_EOL;

            $this->updateLocations($regions);

            return true;
        }

        echo 'Нет данных по регионам'. PHP_EOL;
        return false;
    }

    /**
     * @param array $regions
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function updateLocations(array $regions): bool
    {
        $locations = [];

        foreach ($regions as $region) {

            if (empty($region['id'])) {
                continue;
            }

            $regionUrl = self::SITE_URL . self::REGIONS_API_URL . $region['id'];

            $locationData = json_decode(file_get_contents($regionUrl), true);

            if (isset($locationData['items'])) {

                foreach ($locationData['items'] as $item) {

                    $locations[] = [
                        'id' => $item['id'],
                        'regionId' => $item['region'],
                        'name' => $item['name'],
                    ];
                }
            }
        }

        if ($locations) {

            $columns = array_keys((new Location())->attributes);

            $totalLocations = Yii::$app->db
                ->createCommand()
                ->batchInsert(
                    Location::tableName(),
                    $columns,
                    $locations
                )->execute();

            echo "Сохранено $totalLocations локаций". PHP_EOL;
            return true;
        }

        echo 'Нет данных по локациям'. PHP_EOL;
        return false;
    }

    /**
     * @param int|null $locationId
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateData(int $locationId = null): bool
    {
        if ($locationId) {
            $location = Location::findOne($locationId);
        } else {
            $location = $this->getLocationForUpdate();
        }

        if ($location) {

            $preparedData = $this->getPreparedData($location->id);

            if ($preparedData) {

                $totalUpd = $this->updateDiscountData($preparedData['discountData'], $location->id);

                $this->updateProductData($preparedData['productData']);

                $this->deleteData();

                $location->dataUpdatedAt = time();
                $location->save(false);

                return true;
            }

            echo 'Не удалось получить данные'. PHP_EOL;
            return false;
        }

        echo 'Нет локации для обновления данных'. PHP_EOL;
        return false;
    }

    /**
     * @return Location|null
     */
    public function getLocationForUpdate():? Location
    {
        $location = Location::find()
            ->city()
            ->andWhere([
                '<', 'dataUpdatedAt', time() - self::DAY_TIME
            ])
            ->one();

        return $location;
    }

    /**
     * @param array $items
     * @param int $locationId
     * @return int
     */
    protected function updateDiscountData(array $items, int $locationId): int
    {
        $lastRow = Discount::find()
            ->select(['dateStart'])
            ->where([
                'market' => Discount::FIVE_SHOP,
                'locationId' => $locationId,
            ])
            ->orderBy([
                'dateStart' => SORT_DESC,
            ])
            ->limit(1)
            ->asArray()
            ->one();

        $actualData = [];
        $totalRows = 0;

        foreach ($items as $item) {

            if (!$lastRow || (int)$item['dateStart'] > (int)$lastRow['dateStart']) {

                $actualData[] = $item;
            }
        }

        if (empty($actualData)) {
            echo 'No data'. PHP_EOL;
            return $totalRows;
        }

        // Сохраняем актуальные данные
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $totalRows = Yii::$app->db->createCommand()
                ->batchInsert(
                    Discount::tableName(),
                    Discount::getDataColumns(),
                    $actualData
                )
                ->execute();

            $transaction->commit();

            echo "{$totalRows} discounts added". PHP_EOL;

        } catch (\Exception $e) {

            $errMes = $e->getMessage();
            echo $errMes. PHP_EOL;
            Yii::error($errMes);

            $transaction->rollBack();
        }

        return $totalRows;
    }

    /**
     * @param array $items
     */
    protected function updateProductData(array $items): void
    {
        $existsProductIds = array_map(function($row){
            return $row['pId'];
        }, Product::find()->select('pId')->asArray()->all());

        $actualItems = [];

        foreach ($items as $item) {

            $conditon = !\in_array($item['pId'], $existsProductIds, false);

            if ($conditon) {
                $actualItems[] = $item;
            }
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $totalRows = Yii::$app->db
                ->createCommand()
                ->batchInsert(
                    Product::tableName(),
                    [
                        'pId',
                        'name',
                        'imageBig',
                        'imageSmall',
                        'createdAt'
                    ],
                    $actualItems
                )->execute();

            $transaction->commit();

            echo $totalRows .' products added'. PHP_EOL;

        } catch (\Exception $e) {

            $errMes = $e->getMessage();
            echo $errMes. PHP_EOL;
            Yii::error($errMes);

            $transaction->rollBack();
        }
    }

    /**
     * @param int|null $locationId
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPreparedData(int $locationId = null):? array
    {
        $data = json_decode($this->getData($locationId), true);

        if ($data === null) {
            return null;
        }

        $discountData = [];

        $productData = [];

        if (!empty($data['results'])) {

           foreach ($data['results'] as $result) {

               $discountData[] = $this->getDiscountItem($result, $locationId);

               $productData[] = $this->getProductItem($result);
           }
        }

        return [
            'discountData' => $discountData,
            'productData' => $productData,
        ];
    }

    /**
     * @param int|null $locationId
     * @param int $recordsPerPage
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData(int $locationId = null, int $recordsPerPage = 1000):? string
    {
        $url = implode('', [
            self::SITE_URL,
            self::DISCOUNT_API_URL,
            '&records_per_page='. $recordsPerPage,
        ]);

        $client = new Client([
            'timeout'  => self::REQUEST_TIMEOUT,
        ]);

        $cookies = ['location_id' => $locationId];

        $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($cookies, self::DOMAIN);

        try {
            $response = $client->request('GET', $url, [
                RequestOptions::COOKIES => $cookieJar,
            ]);

        } catch (ConnectException $e) {

            echo 'Request timeout '. $e->getMessage();

        } catch (\Exception $e) {

            echo $e->getMessage() . PHP_EOL;
            Yii::error($e->getMessage());
        }

        if ($response !== null && $response->getStatusCode() === 200) {

            return $response->getBody()->getContents();
        }

        return null;
    }

    /**
     * @param array $result
     * @return array
     */
    public function getDiscountItem(array $result, int $locationId): array
    {
        $item['market']             = Discount::FIVE_SHOP;

        $item['locationId']         = $locationId;

        $item['productId']          = $result['id'];

        $item['productName']        = $result['name'];

        $item['url']                = null;

        $item['description']        = $result['description'];

        $item['regularPrice']       = $result['params']['regular_price'];

        $item['specialPrice']       = $result['params']['special_price'];

        $item['discountPercent']    = $result['params']['discount_percent'];

        $item['dateStart']          = $result['params']['date_start'];

        $item['dateEnd']            = $result['params']['date_end'];

        $item['status']             = Discount::STATUS_ACTIVE;

        $item['createdAt']          = time();

        return $item;
    }

    /**
     * @param array $result
     * @return array
     */
    public function getProductItem(array $result): array
    {
        $item['pId']            = $result['id'];

        $item['productName']    = $result['name'];

        $item['imageSmall']     = $result['image_small'];

        $item['imageBig']       = $result['image_big'];

        $item['createdAt']      = time();

        return $item;
    }

    /**
     *
     */
    public function deleteData(): void
    {
        $res = Discount::deleteAll([
                'and',
                ['market' => Discount::FIVE_SHOP],
                ['status' => Discount::STATUS_ACTIVE],
                ['<', 'dateEnd', time()]
            ]);

        echo "{$res} discounts deleted". PHP_EOL;
    }

    /**
     *
     */
    public function downloadImages(): void
    {
        $products = Product::find()
            ->noPreview()
            ->limit(self::DOWNLOAD_LIMIT)
            ->all();

        foreach ($products as $product) {

            $previewFile = uniqid(Discount::FIVE_SHOP, false) . '.jpg';

            $smallUrl = $product->imageSmall;
            $bigUrl = $product->imageBig;

            $smallPath = self::PREVIEWS_PATH . 'small/' . $previewFile;
            $bigPath = self::PREVIEWS_PATH . 'big/' . $previewFile;

            if (copy($smallUrl, Yii::$app->basePath .'/web'. $smallPath)) {

                $product->previewSmall = $smallPath;

                echo Discount::FIVE_SHOP .' small preview copied successfully' . PHP_EOL;
            }

            if (copy($bigUrl, Yii::$app->basePath .'/web'. $bigPath)) {

                $product->previewBig = $bigPath;

                echo Discount::FIVE_SHOP .' big preview copied successfully' . PHP_EOL;
            }

            $product->save(false);
        }
    }
}