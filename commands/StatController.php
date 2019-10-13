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
    /**
     * todo: сбор статистики слишком сырой, примитивный и в целом нерабочий. Пока отключен
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

            // todo: ввести логирования для статистики

            return true;
        }

        echo 'No stat data'. PHP_EOL;
        return false;
    }
}