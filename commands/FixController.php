<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Discount;

class FixController extends Controller
{
    public function actionMarketCase()
    {
        $totalUpdated = Discount::updateAll([
            'market' => 'five_shop'
        ]);

        echo "$totalUpdated rows updated". PHP_EOL;
    }
}