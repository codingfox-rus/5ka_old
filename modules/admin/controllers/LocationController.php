<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\Region;
use app\models\Location;
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

    public function actionViewStat(int $id)
    {
        $model = $this->findModel($id);

        return $this->render('view-stat', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Location
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Location
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Локация не найдена');
    }
}