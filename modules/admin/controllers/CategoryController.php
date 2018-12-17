<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Category;
use app\models\CategorySearch;
use app\models\CategoryWordKey;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends MainController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'add-key' => ['POST'],
                    'update-key' => ['POST'],
                    'delete-key' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return CategoryWordKey
     * @throws NotFoundHttpException
     */
    protected function findKey($id)
    {
        if (($model = CategoryWordKey::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Ключ не найден');
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionLoadAddKeyForm(int $id)
    {
        $category = $this->findModel($id);

        return $this->renderPartial('_partial/form-add-key', [
            'category' => $category,
        ]);
    }

    /**
     * @param int $id
     * @return array
     */
    public function actionAddKey(int $id)
    {
        $key = new CategoryWordKey();

        $key->categoryId = $id;

        if ($key->load(Yii::$app->request->post()) && $key->save()) {

            $res = ['status' => 'success'];

        } else {

            $res = ['status' => 'error'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $res;
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionLoadUpdateKeyForm(int $id)
    {
        $key = CategoryWordKey::findOne($id);

        return $this->renderPartial('_partials/form-update-key', [
            'key' => $key
        ]);
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateKey(int $id)
    {
        $key = $this->findKey($id);

        if ($key->load(Yii::$app->request->post()) && $key->save()) {

            $res = ['status' => 'success'];

        } else {

            $res = ['status' => 'error'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $res;
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteKey(int $id)
    {
        $key = $this->findKey($id);

        $key->delete();

        return true;
    }
}
