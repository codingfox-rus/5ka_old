<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Stat;
use app\models\Location;
use app\models\Discount;
use app\models\Feedback;

class StatController extends Controller
{

    public function actionProcessLocation(): bool
    {
        $location = Location::findOne(['needToProcess' => 1]);

        if ($location === null) {
            echo 'Нет локаций для сбор статистики'. PHP_EOL;
            return false;
        }

        $discounts = Discount::find()
            ->select([
                'locationId',
                'productId',
                'regularPrice',
                'specialPrice',
                'dateStart',
                'dateEnd',
            ])
            ->location($location->id)
            ->indexBy('productId')
            ->asArray()
            ->all();

        $stat = Stat::find()
            ->location($location->id)
            ->indexBy('productId')
            ->asArray()
            ->all();

        // Сначала формируем записи по новым скидкам, которых еще нет в статистике
        $freshProductIds = array_diff(array_keys($discounts), array_keys($stat));

        $freshRows = [];

        foreach ($freshProductIds as $pId) {

            $discount = $discounts[$pId];

            $data = [
                'regularPrice' => $discount['regularPrice'],
                'specialPrice' => $discount['specialPrice'],
                'dateStart' => $discount['dateStart'],
                'dateEnd' => $discount['dateEnd'],
            ];

            $freshRows[] = [
                'locationId'    => $location->id,
                'productId'     => $pId,
                'data'          => json_encode([$data]),
                'nextTurnAt'    => $discount['dateEnd'] + 86400,
                'createdAt'     => time(),
                'updatedAt'     => time(),
            ];
        }

        $existingRows = [];

        foreach ($stat as $pId => $stItem) {

            $data = json_decode($stItem['data'], true) ?? [];

            $discount = $discounts[$pId] ?? null;

            $nextTurnAt = $stItem['nextTurnAt'];
            $updatedAt = $stItem['updatedAt'];

            if ($discount && $discount['dateEnd'] > $nextTurnAt) {

                $data[] = [
                    'regularPrice' => $discount['regularPrice'],
                    'specialPrice' => $discount['specialPrice'],
                    'dateStart' => $discount['dateStart'],
                    'dateEnd' => $discount['dateEnd'],
                ];

                $nextTurnAt = $discount['dateEnd'] + 86400;
                $updatedAt = time();
            }

            $existingRows[] = [
                'locationId'    => $location->id,
                'productId'     => $pId,
                'data'          => json_encode($data),
                'nextTurnAt'    => $nextTurnAt,
                'createdAt'     => $stItem['createdAt'],
                'updatedAt'     => $updatedAt,
            ];
        }

        $allRows = array_merge($freshRows, $existingRows);

        if ($allRows) {

            $del = Stat::deleteAll([
                'locationId' => $location->id,
            ]);

            echo $del .' rows deleted'. PHP_EOL;

            $ins = Yii::$app->db->createCommand()
                ->batchInsert(
                    Stat::tableName(),
                    array_keys($allRows[0]),
                    $allRows
                )->execute();

            echo $ins .' rows inserted'. PHP_EOL;

            $location->needToProcess = 0;
            $location->save(false);

            return true;
        }

        echo 'No stat data'. PHP_EOL;
        return false;
    }

    /**
     * Собираем статистику по продуктами определенной локации
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionProcessLocationOld(): bool
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
                'dateStart',
                'dateEnd',
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

            $alert = new Feedback();
            $alert->name = 'Статистика';
            $alert->email = Yii::$app->params['adminEmail'];
            $alert->subject = 'Обновление статистики';
            $alert->message = "Обновлена статистика для {$location->name}. Количество записей: {$ins}";

            if (!$alert->save(false)) {
                $errors = json_encode($alert->errors);
                Yii::error($errors);
                echo $errors . PHP_EOL;
            }

        } else {
            echo 'Нет данных для статистики'. PHP_EOL;
        }

        return true;
    }


}