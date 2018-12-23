<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\db\Exception;
use app\models\Discount;

class DiscountController extends Controller
{
    public function actionSaveData()
    {
        $marketClasses = Discount::getMarketClasses();

        foreach ($marketClasses as $market => $class) {

            /** @var \app\interfaces\iMarket $handler */
            $handler = new $class;

            $data = $handler->getData();

            $path = $handler->getFilePath();

            if (file_put_contents($path, $data)) {

                echo "Data for ${$market} successfully saved". PHP_EOL;

            } else {

                echo "Error on saving data for {$market}". PHP_EOL;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function actionHandleData()
    {
        $marketClasses = Discount::getMarketClasses();

        foreach ($marketClasses as $market => $class) {

            /** @var \app\interfaces\iMarket $handler */
            $handler = new $class;

            $preparedData = $handler->getPreparedData();

            $transaction = Yii::$app->db->beginTransaction();

            try {
                Discount::deleteAll(['market' => Discount::FIVE_SHOP]);

                Yii::$app->db->createCommand()->batchInsert(
                    Discount::tableName(),
                    Discount::getDataColumns(),
                    $preparedData
                )->execute();

                $transaction->commit();

            } catch (Exception $e) {

                $transaction->rollBack();

                Yii::error($e->getMessage());
            }
        }
    }
}
