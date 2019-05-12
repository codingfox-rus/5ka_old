<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Region;
use app\models\LocationSearch;

class LocationController extends MainController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $regions = ArrayHelper::map(Region::find()->asArray()->all(), 'id', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'regions' => $regions,
        ]);
    }
}