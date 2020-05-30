<?php
namespace app\modules\admin\controllers;

use Yii;
use Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\Discount;
use app\models\DiscountSearch;

/**
 * Class DiscountController
 * @package app\modules\admin\controllers
 */
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
    public function actionIndex(): string
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
     * @throws Exception
     */
    public function actionView(int $id): string
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
    protected function findModel(int $id): Discount
    {
        if (($model = Discount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запись не найдена');
    }
}