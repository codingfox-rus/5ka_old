<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\parsers\FiveShop;

class ConsoleController extends Controller
{
    /** @var FiveShop */
    public $fiveShop;

    public function init()
    {
        parent::init();

        $this->fiveShop = Yii::$app->get('fiveShop');
    }

    /**
     * @return bool
     */
    public function actionGetData() : bool
    {
        $data = $this->fiveShop->getData();

        if (file_put_contents(__DIR__ .'/../data/discounts.json', $data)) {

            echo 'Discount data saved successfully'. PHP_EOL;

            return true;
        }

        echo 'Error on save discount data'. PHP_EOL;

        return false;
    }
}
