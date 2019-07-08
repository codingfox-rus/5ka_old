<?php
namespace app\modules\admin\controllers;

use Yii;
use app\models\Region;
use app\models\RegionSearch;
use app\models\Location;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends MainController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [

                ],
            ],
        ];
    }

    /**
     * Lists all Region models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Region model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $id
     * @return Region
     * @throws NotFoundHttpException
     */
    protected function findModel($id): Region
    {
        if (($model = Region::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Регион не найден');
    }

    /**
     * @param $id
     * @return Location
     * @throws NotFoundHttpException
     */
    protected function findLocation($id): Location
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Локация не найден');
    }

    /**
     * @return int
     * @throws NotFoundHttpException
     */
    public function actionToggleLocation(): int
    {
        $id = Yii::$app->request->post('id');

        $location = $this->findLocation($id);
        $location->isEnabled = !$location->isEnabled;
        $location->save(false);

        return $location->isEnabled;
    }
}
