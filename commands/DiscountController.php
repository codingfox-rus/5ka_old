<?php
namespace app\commands;

use app\components\markets\FiveShop;
use Yii;
use yii\console\Controller;
use app\models\Discount;

class DiscountController extends Controller
{
    const PREVIEWS_PATH = '/previews/five_shop/';
    const DOWNLOAD_LIMIT = 50;

    public function actionSaveData()
    {
        $marketClasses = Discount::getMarketClasses();

        foreach ($marketClasses as $market => $class) {

            /** @var \app\interfaces\iMarket $handler */
            $handler = new $class;

            $data = $handler->getData();

            $path = $handler->getFilePath();

            if (file_put_contents($path, $data)) {

                echo "Data for {$market} successfully saved". PHP_EOL;

            } else {

                echo "Error on saving data for {$market}". PHP_EOL;
            }
        }
    }

    public function actionUpdateData(string $market)
    {
        $handleClass = Discount::getMarketClasses()[$market];

        /** @var \app\interfaces\iMarket $handler */
        $handler = new $handleClass;

        $preparedData = $handler->getPreparedData();

        $lastRow = Discount::find()
            ->select(['dateStart'])
            ->where([
                'market' => $market,
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

            $totalRows = count($actualData);

            echo "{$totalRows} added". PHP_EOL;

        } catch (\Exception $e) {

            $errMes = $e->getMessage();

            echo $errMes. PHP_EOL;

            Yii::error($errMes);

            $transaction->rollBack();
        }
    }

    /**
     * Меняем статус у неактуальный (просроченных) скидок
     */
    public function actionArchiveData()
    {
        $res = Discount::updateAll(
            [
                'status' => 2
            ],
            'status = 1 and dateEnd < :now',
            [
                ':now' => time(),
            ]
        );

        echo "{$res} rows updated". PHP_EOL;
    }

    public function actionDownloadImages()
    {
        $discounts = Discount::find()
            ->active()
            ->noPreviews()
            ->limit(self::DOWNLOAD_LIMIT)
            ->all();

        foreach ($discounts as $discount) {

            $previewFile = uniqid('', false) .'.jpg';

            $smallUrl = FiveShop::SITE_URL . $discount->imageSmall;
            $bigUrl = FiveShop::SITE_URL . $discount->imageBig;

            $smallPath = self::PREVIEWS_PATH . 'small/'. $previewFile;
            $bigPath = self::PREVIEWS_PATH . 'big/'. $previewFile;

            if (copy($smallUrl, Yii::$app->basePath .'/web'. $smallPath)) {

                $discount->previewSmall = $smallPath;

                echo 'Small preview copied successfully'. PHP_EOL;
            }

            if (copy($bigUrl, Yii::$app->basePath .'/web'. $bigPath)) {

                $discount->previewBig = $bigPath;

                echo 'Big preview copied successfully'. PHP_EOL;
            }

            $discount->save(false);
        }

    }
}
