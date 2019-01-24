<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Discount;

class DiscountController extends Controller
{
    public function actionSaveFile()
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
        $market = trim(strtoupper($market));

        $handleClass = Discount::getMarketClasses()[$market];

        /** @var \app\interfaces\iMarket $handler */
        $handler = new $handleClass;

        $preparedData = $handler->getPreparedData();

        $lastRow = Discount::find()
            ->select(['dateStart'])
            ->orderBy([
                'dateStart' => SORT_DESC,
            ])
            ->limit(1)
            ->asArray()
            ->all();

        $actualData = [];

        foreach ($preparedData as $item) {

            if ((int)$item['dateStart'] > (int)$lastRow['dateStart']) {

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
}
