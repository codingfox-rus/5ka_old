<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\Discount;
use app\models\DiscountSearch;
use app\models\Stat;
use yii\web\Response;

class DiscountController extends MainController
{
    /**
     * @return array
     */
    public function behaviors() : array
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'attach-to-category' => ['POST'],
                ],
            ],
        ];

        return array_merge(parent::behaviors(), $behaviors);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiscountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function actionView(int $id)
    {
        $prev = Discount::findOne($id + 1);

        $model = $this->findModel($id);

        $next = Discount::findOne($id - 1);

        return $this->render('view', [
            'prev' => $prev,
            'model' => $model,
            'next' => $next,
        ]);
    }

    /**
     * @param int $id
     * @return Discount
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id)
    {
        if (($model = Discount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запись не найдена');
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionEnableStat(int $id): Response
    {
        $discount = $this->findModel($id);

        $stat = new Stat();
        $stat->locationId = $discount->locationId;
        $stat->productId = $discount->productId;
        $stat->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDisableStat(int $id): Response
    {
        $discount = $this->findModel($id);

        $stat = Stat::findOne([
            'locationId' => $discount->locationId,
            'productId' => $discount->productId,
        ]);

        if ($stat) {
            $stat->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}