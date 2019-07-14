<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\Region;
use app\models\Location;
use app\models\LocationSearch;
use app\models\Stat;

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

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewStat(int $id): string
    {
        $model = $this->findModel($id);

        $query = Stat::find()
            ->location($id)
            ->withData();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('view-stat', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $locationId
     * @param int $productId
     * @return string
     */
    public function actionViewProductStat(int $locationId, int $productId): string
    {
        $stat = Stat::findOne([
            'locationId' => $locationId,
            'productId' => $productId,
        ]);

        return $this->render('view-product-stat', [
            'stat' => $stat,
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