<?php
namespace app\components\markets;

use app\components\DiscountHelper;
use app\models\DiscountHistory;
use app\models\DiscountSearch;
use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ConnectException;
use app\models\Region;
use app\models\Location;
use app\models\Discount;
use app\models\Product;
use app\models\Settings;
use yii\helpers\ArrayHelper;

class FiveShop
{
    public const SITE_URL           = 'https://5ka.ru';
    public const DOMAIN             = '.5ka.ru';

    public const REGIONS_API_URL    = '/api/regions/';
    public const PREVIEWS_PATH      = '/previews/five_shop/';

    public const DISCOUNT_API_V1_URL   = '/api/special_offers/';
    public const DISCOUNT_API_V2_URL   = '/api/v2/special_offers/';

    public const DEFAULT_LOCATION_ID    = 2223;
    public const REQUEST_TIMEOUT        = 30;
    public const DAY_TIME               = 86400;
    public const DOWNLOAD_LIMIT         = 50;

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

            $totalLocations = Yii::$app->db
                ->createCommand()
                ->batchInsert(
                    Location::tableName(),
                    array_keys($locations[0]),
                    $locations
                )->execute();

            echo "Сохранено $totalLocations локаций". PHP_EOL;
            return true;
        }

        echo 'Нет данных по локациям'. PHP_EOL;
        return false;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateData(): bool
    {
        $location = $this->getLocationForUpdate();

        /** @var Settings $settings */
        $settings = Settings::find()->one();

        if ($location && $settings) {

            $preparedData = $this->getPreparedData($location->id, $settings->apiVersion);

            if ($preparedData) {

                $totalUpd = $this->updateDiscountData($preparedData['discountData'], $location->id);

                $this->updateProductData($preparedData['productData']);

                $this->deleteData();

                if ($totalUpd > 0) {

                    $location->needToProcess = 1;
                    $location->dataUpdatedAt = time();
                    $location->save(false);

                    if ($location->region) {
                        $location->region->updatedAt = time();
                        $location->region->save(false);
                    }

                    echo $totalUpd .' discounts added'. PHP_EOL;
                }

                return true;
            }

            echo 'Не удалось получить данные'. PHP_EOL;
            return false;
        }

        echo 'Нет локации для обновления данных или не инициализированы настройки'. PHP_EOL;
        return false;
    }

    /**
     * @return Location|null
     */
    public function getLocationForUpdate():? Location
    {
        $location = Location::find()
            ->enabled()
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

            if ($lastRow === null || (int)$item['dateStart'] > (int)$lastRow['dateStart']) {

                $actualData[] = $item;
            }
        }

        if (empty($actualData)) {
            echo 'No data'. PHP_EOL;
            return $totalRows;
        }

        $locationsList = $this->getLocationsList();

        // Сохраняем актуальные данные
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $fields = array_keys((new Discount())->attributes);
            $fields = array_diff($fields, ['id']);

            $totalRows = Yii::$app->db->createCommand()
                ->batchInsert(
                    Discount::tableName(),
                    $fields,
                    $actualData
                )
                ->execute();

            // Сохраняем скидки для истории
            Yii::$app->db->createCommand()
                ->batchInsert(
                    DiscountHistory::tableName(),
                    $fields,
                    $actualData
                )
                ->execute();

            $transaction->commit();

            $locationName = $locationsList[$locationId] ?? null;

            if ($locationName) {
                echo 'Обновлены скидки по '. $locationName . PHP_EOL;
            }

        } catch (\Exception $e) {

            $errMes = $e->getMessage();
            echo $errMes. PHP_EOL;
            Yii::error($errMes);

            $transaction->rollBack();
        }

        return $totalRows;
    }

    /**
     * @return array
     */
    protected function getLocationsList(): array
    {
        return ArrayHelper::map(Location::find()->asArray()->all(), 'id', 'name');
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

            $condition = !\in_array($item['pId'], $existsProductIds, false);

            if ($condition) {
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
     * @param int $locationId
     * @param int $apiVersion
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPreparedData(int $locationId, int $apiVersion):? array
    {
        $res = [
            'discountData' => [],
            'productData' => []
        ];

        if ($apiVersion === Settings::API_V1) {

            $res = $this->getPreparedDataV1($locationId);

        } else if ($apiVersion === Settings::API_V2) {

            $res = $this->getPreparedDataV2($locationId);
        }

        return $res;
    }

    /**
     * @param $locationId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPreparedDataV1($locationId): array
    {
        $parsedData = $this->getParsedData($locationId, Settings::API_V1);
        $discountData = [];
        $productData = [];

        foreach ($parsedData as $dataItem) {

            foreach ($dataItem as $result) {

                $discountData[] = $this->getDiscountItemV1($result, $locationId);

                $productData[] = $this->getProductItemV1($result);
            }
        }

        return [
            'discountData' => $discountData,
            'productData' => $productData,
        ];
    }

    /**
     * @param array $result
     * @param int $locationId
     * @return array
     */
    public function getDiscountItemV1(array $result, int $locationId): array
    {
        $item['locationId']         = $locationId;
        $item['productId']          = $result['params']['id'];
        $item['productName']        = $result['name'];
        $item['regularPrice']       = $result['params']['regular_price'];
        $item['specialPrice']       = $result['params']['special_price'];
        $item['discountPercent']    = $result['params']['discount_percent'];
        $item['dateStart']          = $result['params']['date_start'];
        $item['dateEnd']            = $result['params']['date_end'];
        $item['jsonData']           = json_encode($result, JSON_UNESCAPED_UNICODE);
        $item['status']             = Discount::STATUS_ACTIVE;
        $item['createdAt']          = time();

        return $item;
    }

    /**
     * @param array $result
     * @return array
     */
    public function getProductItemV1(array $result): array
    {
        $item['pId']            = $result['params']['id'];
        $item['productName']    = $result['name'];
        $item['imageSmall']     = $result['image_small'];
        $item['createdAt']      = time();

        return $item;
    }

    /**
     * @param $locationId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPreparedDataV2($locationId): array
    {
        $parsedData = $this->getParsedData($locationId, Settings::API_V2);
        $discountData = [];
        $productData = [];

        foreach ($parsedData as $dataItem) {

            foreach ($dataItem as $result) {

                $discountData[] = $this->getDiscountItemV2($result, $locationId);

                $productData[] = $this->getProductItemV2($result);
            }
        }

        return [
            'discountData' => $discountData,
            'productData' => $productData,
        ];
    }

    /**
     * @param array $result
     * @param int $locationId
     * @return array
     */
    public function getDiscountItemV2(array $result, int $locationId): array
    {
        $regularPrice = $result['current_prices']['price_reg__min'] ?? 0;

        $specialPrice = $result['current_prices']['price_promo__min'] ?? 0;

        $discountPercent = $this->getDiscountPercent($regularPrice, $specialPrice);

        $item['locationId']         = $locationId;
        $item['productId']          = $result['id'];
        $item['productName']        = $result['name'];
        $item['regularPrice']       = $regularPrice;
        $item['specialPrice']       = $specialPrice;
        $item['discountPercent']    = $discountPercent;
        $item['dateStart']          = strtotime($result['promo']['date_begin']);
        $item['dateEnd']            = strtotime($result['promo']['date_end']);
        $item['jsonData']           = json_encode($result, JSON_UNESCAPED_UNICODE);
        $item['status']             = Discount::STATUS_ACTIVE;
        $item['createdAt']          = time();

        return $item;
    }

    /**
     * @param $regularPrice
     * @param $specialPrice
     * @return float|int
     */
    protected function getDiscountPercent($regularPrice, $specialPrice): float
    {
        if ($regularPrice > 0 && $specialPrice > 0) {

            $diff = $regularPrice - $specialPrice;

            if ($diff > 0) {

                return round(($diff / $regularPrice) * 100, 2);
            }
        }

        return 0;
    }

    /**
     * @param array $result
     * @return array
     */
    public function getProductItemV2(array $result): array
    {
        $item['pId']            = $result['id'];
        $item['productName']    = $result['name'];
        $item['imageSmall']     = $result['img_link'];
        $item['createdAt']      = time();

        return $item;
    }

    /**
     * @param $locationId
     * @param $apiVersion
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getParsedData($locationId, $apiVersion): array
    {
        $apiData = json_decode($this->getData($locationId, $apiVersion), true);
        $results = [];

        if (!empty($apiData['next'])) {

            $results[] = $apiData['results'];

            while ($apiData['next'] !== null) {

                [, $pageNum] = explode('=', $apiData['next']);

                $apiData = json_decode($this->getData($locationId, $apiVersion, $pageNum), true);

                $results[] = $apiData['results'];
            }
        }

        if (empty($results)) {
            echo 'Не удалось получить данные по API'. PHP_EOL;
        }

        return $results;
    }

    /**
     * @param $locationId
     * @param int $apiVersion
     * @param int $page
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData($locationId, int $apiVersion, int $page = 1):? string
    {
        $apiUrl = null;

        if ($apiVersion === Settings::API_V1) {
            $apiUrl = self::DISCOUNT_API_V1_URL;
        } else if ($apiVersion === Settings::API_V2) {
            $apiUrl = self::DISCOUNT_API_V2_URL;
        }

        $url = implode('', [
            self::SITE_URL,
            $apiUrl,
            '?page='. $page,
        ]);

        $client = new Client([
            'timeout'  => self::REQUEST_TIMEOUT,
        ]);

        $cookies = ['location_id' => $locationId];

        $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($cookies, self::DOMAIN);

        $response = null;

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
     *
     */
    public function deleteData(): void
    {
        $res = Discount::deleteAll([
                'and',
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

            $previewFile = uniqid('five_shop', false) . '.jpg';

            $smallUrl = $product->imageSmall;

            $smallPath = self::PREVIEWS_PATH . 'small/' . $previewFile;

            if (copy($smallUrl, Yii::$app->basePath .'/web'. $smallPath)) {

                $product->previewSmall = $smallPath;

                echo 'Small preview copied successfully' . PHP_EOL;
            }

            $product->save(false);
        }
    }
}