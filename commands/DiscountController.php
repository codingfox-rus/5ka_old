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

    /** @var Bristol */
    public $bristol;

    public function init()
    {
        $this->fiveShop = Yii::$app->get('fiveShop');
        //$this->bristol = Yii::$app->get('bristol');
    }

    /**
     * Сохраняем данные
     */
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

    /**
     * Обновляем данные
     */
    public function actionUpdateData()
    {
        $this->fiveShop->updateData();
    }

    /**
     * Архивируем данные
     */
    public function actionArchiveData()
    {
        $this->fiveShop->archiveData();
    }

    /**
     * Скачиваем изображения товаров, чтобы не обращаться к родительскому сайту
     */
    public function actionDownloadImages()
    {
        $this->fiveShop->downloadImages();
    }
}
