<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Discount;
use app\components\markets\FiveShop;
use app\components\markets\Bristol;

/**
 * Class DiscountController
 * @package app\commands
 */
class DiscountController extends Controller
{
    /** @var FiveShop */
    public $fiveShop;

    public function init()
    {
        $this->fiveShop = Yii::$app->get('fiveShop');
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionUpdateRegions(): void
    {
        $this->fiveShop->updateRegions();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionUpdateData(): void
    {
        $this->fiveShop->updateData();
    }

    /**
     * Скачиваем изображения товаров, чтобы не обращаться к родительскому сайту
     */
    public function actionDownloadImages(): void
    {
        $this->fiveShop->downloadImages();
    }

    /**
     * Удаляем все скидки (в целях отладки)
     */
    public function actionDropAll(): void
    {
        $totalDeleted = Discount::deleteAll();

        echo $totalDeleted .' rows deleted'. PHP_EOL;
    }

    /**
     * Устанавливаем теги для скидок
     */
    public function actionSetTags(): void
    {

    }
}
