<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\markets\FiveShop;

class DataController extends Controller
{
    /** @var FiveShop */
    public $fiveShop;

    public function init()
    {
        $this->fiveShop = new FiveShop();
    }

    public function actionUpdateRegions(): void
    {
        $this->fiveShop->updateRegions();
    }
}