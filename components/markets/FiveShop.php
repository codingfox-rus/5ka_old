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
use app\models\Stat;

class FiveShop implements \app\interfaces\iMarket
{
    public const SITE_URL           = 'https://5ka.ru';
    public const DOMAIN             = '.5ka.ru';

    public const REGIONS_API_URL    = '/api/regions/';
    public const DISCOUNT_API_URL   = '/api/v2/special_offers/';
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

        if ($location) {

            $preparedData = $this->getPreparedData($location->id);

            if ($preparedData) {

                $totalUpd = $this->updateDiscountData($preparedData['discountData'], $location->id);

                $this->updateProductData($preparedData['productData']);

                $this->deleteData();

                $statLocationIds = $this->getStatLocationIds();

                if (\in_array($location->id, $statLocationIds, false)) {
                    $location->needToProcess = 1;
                }

                $location->dataUpdatedAt = time();
                $location->save(false);

                if ($location->region) {
                    $location->region->updatedAt = time();
                    $location->region->save(false);
                }

                echo $totalUpd .' discounts added'. PHP_EOL;
                return true;
            }

            echo 'Не удалось получить данные'. PHP_EOL;
            return false;
        }

        echo 'Нет локации для обновления данных'. PHP_EOL;
        return false;
    }

    /**
     * @return array
     */
    protected function getStatLocationIds(): array
    {
        return Stat::find()
            ->select('locationId')
            ->distinct()
            ->asArray()
            ->column();
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

            if ($lastRow === null || (int)$item['dateStart'] > (int)$lastRow['dateStart']) {

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
        $apiData = json_decode($this->getData($locationId), true);
        $results = [];

        if (!empty($apiData['next'])) {

            $results[] = $apiData['results'];

            while ($apiData['next'] !== null) {

                [, $pageNum] = explode('=', $apiData['next']);

                $apiData = json_decode($this->getData($locationId, $pageNum), true);

                $results[] = $apiData['results'];
            }
        }

        if (empty($results)) {
            echo 'Не удалось получить данные по API'. PHP_EOL;
            return null;
        }

        $discountData = [];
        $productData = [];

        foreach ($results as $resultGroup) {

            foreach ($resultGroup as $result) {

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
     * @param int $page
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData(int $locationId = null, int $page = 1):? string
    {
        $url = implode('', [
            self::SITE_URL,
            self::DISCOUNT_API_URL,
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
     * @param array $result
     * @param int $locationId
     * @return array
     */
    public function getDiscountItem(array $result, int $locationId): array
    {
        $regularPrice = $result['current_prices']['price_reg__min'] ?? 0;

        $specialPrice = $result['current_prices']['price_promo__min'] ?? 0;

        $discountPercent = $this->getDiscountPercent($regularPrice, $specialPrice);

        $item['market']             = Discount::FIVE_SHOP;

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
    protected function getDiscountPercent($regularPrice, $specialPrice)
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
    public function getProductItem(array $result): array
    {
        $item['pId']            = $result['id'];

        $item['productName']    = $result['name'];

        $item['imageSmall']     = $result['img_link'];

        $item['imageBig']       = $result['img_link'];

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