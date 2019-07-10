<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;
use app\models\Region;
use app\models\RegionSearch;
use app\models\Location;
use app\models\Stat;

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
        $locationRows = Location::find()
            ->select(['id', 'name'])
            ->andWhere(['regionId' => $id])
            ->asArray()
            ->all();

        $locations = ArrayHelper::map($locationRows, 'id', 'name');

        $statRows = Stat::find()
            ->select(['locationId', 'count(*) as total'])
            ->where([
                'in', 'locationId', array_keys($locations)
            ])
            ->andWhere([
                'not', ['data' => null]
            ])
            ->groupBy('locationId')
            ->asArray()
            ->all();

        $stat = [];

        foreach ($statRows as $item) {

            $stat[$item['locationId']] = [
                'name' => $locations[$item['locationId']],
                'total' => $item['total'],
            ];
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'stat' => $stat,
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

    /**
     * @param int $id
     * @return Response
     */
    public function actionEnableAllLocations(int $id): Response
    {
        Location::updateAll([
            'isEnabled' => 1,
        ], [
            'regionId' => $id
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDisableAllLocations(int $id): Response
    {
        Location::updateAll([
            'isEnabled' => 0,
        ], [
            'regionId' => $id
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }
}
