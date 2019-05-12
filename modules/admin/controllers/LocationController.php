<?php
namespace app\modules\admin\controllers;

use Yii;
//use app\models\LocationSearch;

class LocationController extends MainController
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}