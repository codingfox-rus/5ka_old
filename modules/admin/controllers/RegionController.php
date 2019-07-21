<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\Region;
use app\models\RegionSearch;
use app\models\Location;
use app\models\Stat;
use app\models\Discount;

/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends MainController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'set-capital' => ['post'],
                    'disable-total-locations' => ['post'],
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

        $totalEnabledLocations = Location::find()->enabled()->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalEnabledLocations' => $totalEnabledLocations,
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
        $stat = $this->getStat($id);
        $totalDiscounts = $this->getTotalDiscounts($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'stat' => $stat,
            'totalDiscounts' => $totalDiscounts,
        ]);
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getStat(int $id): array
    {
        $locations = $this->getLocationsList($id);

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

        return $stat;
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getTotalDiscounts(int $id): array
    {
        $locations = $this->getLocationsList($id);

        $discountRows = Discount::find()
            ->select(['locationId', 'count(*) as total'])
            ->where([
                'in', 'locationId', array_keys($locations)
            ])
            ->groupBy('locationId')
            ->asArray()
            ->all();

        return ArrayHelper::map($discountRows, 'locationId', 'total');
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getLocationsList(int $id): array
    {
        $locationRows = Location::find()
            ->select(['id', 'name'])
            ->andWhere(['regionId' => $id])
            ->asArray()
            ->all();

        return ArrayHelper::map($locationRows, 'id', 'name');
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

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewSetCapitalForm(int $id): string
    {
        $model = $this->findModel($id);

        return $this->renderPartial('_partials/form-set-capital', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSetCapital(int $id): Response
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return Response
     */
    public function actionEnableOnlyCapitals(): Response
    {
        $capitalIds = Region::find()
            ->select('capitalLocationId')
            ->where([
                'not', ['capitalLocationId' => null]
            ])
            ->column();

        Location::updateAll([
            'isEnabled' => 1,
        ], [
            'in', 'id', $capitalIds
        ]);

        Location::updateAll([
            'isEnabled' => 0,
        ], [
            'not in', 'id', $capitalIds
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return Response
     */
    public function actionDisableTotalLocations(): Response
    {
        Location::updateAll([
            'isEnabled' => 0,
        ]);

        return $this->redirect(Yii::$app->request->referrer);
    }
}
