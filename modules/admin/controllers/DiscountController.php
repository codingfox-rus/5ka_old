<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\Discount;
use app\models\DiscountSearch;
use app\models\Category;

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

    public function actionLoadCreateCategoryForm()
    {
        $model = new Category();

        return $this->renderPartial('/_partials/form-create-category', [
            'model' => $model
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function actionLoadAttachToCategoryForm(int $id)
    {
        $model = $this->findModel($id);

        $categories = Category::find()->all();

        return $this->renderPartial('_partials/form-attach-to-category', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionAttachToCategory(int $id)
    {
        $model = $this->findModel($id);

        $model->categoryId = Yii::$app->request->post('categoryId');

        if ($model->save()) {
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'error'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $res;
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
}