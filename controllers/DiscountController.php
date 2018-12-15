<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\DiscountFiveShopSearch;

class DiscountController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiscountFiveShopSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}