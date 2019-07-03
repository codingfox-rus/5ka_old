<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Discount;
use app\components\markets\FiveShop;
use app\components\markets\Bristol;

class DiscountController extends Controller
{
    /** @var FiveShop */
    public $fiveShop;

    public function init()
    {
        $this->fiveShop = Yii::$app->get('fiveShop');
    }

    /**
     * @param int|null $locationId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionUpdateData(int $locationId = null)
    {
        if ($locationId === null) {
            $locationId = FiveShop::DEFAULT_LOCATION_ID;
        }

        $this->fiveShop->updateData($locationId);
    }

    /**
     * Скачиваем изображения товаров, чтобы не обращаться к родительскому сайту
     */
    public function actionDownloadImages()
    {
        $this->fiveShop->downloadImages();
    }

    /**
     * Удаляем все скидки (в целях отладки)
     */
    public function actionDropAll()
    {
        $totalDeleted = Discount::deleteAll([
            'market' => Discount::FIVE_SHOP,
        ]);

        echo $totalDeleted .' rows deleted'. PHP_EOL;
    }
}
