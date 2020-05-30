<?php
namespace app\commands;

use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\console\Controller;
use app\models\Discount;
use app\components\markets\FiveShop;
use yii\db\Exception;

/**
 * Class DiscountController
 * @package app\commands
 * @property FiveShop $fiveShop
 */
class DiscountController extends Controller
{
    public $fiveShop;

    public function init(): void
    {
        $this->fiveShop = Yii::$app->get('fiveShop');
    }

    /**
     * @throws Exception
     */
    public function actionUpdateRegions(): void
    {
        $this->fiveShop->updateRegions();
    }

    /**
     * @throws GuzzleException
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
