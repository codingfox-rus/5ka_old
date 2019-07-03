<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Stat;
use app\models\Location;
use app\models\Discount;

class StatController extends Controller
{
    /**
     * Собираем статистику по продуктами определенной локации
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionProcessLocation(): bool
    {
        $location = Location::findOne(['needToProcess' => 1]);

        if ($location === null) {
            echo 'Нет локаций для сбор статистики'. PHP_EOL;
            return false;
        }

        $statRows = Stat::find()
            ->select(['productId', 'data', 'nextTurnAt'])
            ->location($location->id)
            ->indexBy('productId')
            ->asArray()
            ->all();

        $statProductIds = array_keys($statRows);

        $discounts = Discount::find()
            ->select([
                'locationId',
                'productId',
                'regularPrice',
                'specialPrice',
                'dateEnd'
            ])
            ->location($location->id)
            ->andWhere([
                'in', 'productId', $statProductIds
            ])
            ->asArray()
            ->all();

        $updatedProductData = [];

        foreach ($discounts as $discount) {

            foreach ($statRows as $stat) {

                if ((int)$discount['productId'] === (int)$stat['productId']) {

                    if ($discount['dateEnd'] <= $stat['nextTurnAt']) {
                        continue;
                    }

                    $productStat = json_decode($stat['data'], true);

                    $productStat[] = [
                        'regularPrice' => $discount['regularPrice'],
                        'specialPrice' => $discount['specialPrice'],
                        'dateStart' => $discount['dateStart'],
                        'dateEnd' => $discount['dateEnd'],
                    ];

                    $updatedProductData[$stat['productId']] = [
                        'locationId' => $stat['locationId'],
                        'productId' => $stat['productId'],
                        'data' => json_encode($productStat, JSON_UNESCAPED_UNICODE),
                        'nextTurnAt' => $discount['dateEnd'],
                        'createdAt' => time(),
                        'updatedAt' => time(),
                    ];
                }
            }
        }

        if ($updatedProductData) {

            $updatedProductIds = array_keys($updatedProductData);

            $del = Stat::deleteAll([
                'and',
                ['locationId' => $location->id],
                ['in', 'productId', $updatedProductIds]
            ]);

            echo $del .' stat rows deleted'. PHP_EOL;

            $dataForInsert = array_values($updatedProductData);

            $ins = Yii::$app->db->createCommand()
                ->batchInsert(
                    Stat::tableName(),
                    array_keys($dataForInsert[0]),
                    $dataForInsert
                )->execute();

            echo $ins .' stat rows inserted'. PHP_EOL;

        } else {
            echo 'Нет данных для статистики'. PHP_EOL;
        }

        return true;
    }


}