<?php
namespace app\modules\admin\controllers;

use Yii;
use app\models\Category;
use app\models\DiscountFiveShop;
use app\models\DiscountFiveShopSearch;

class DiscountFiveShopController extends MainController
{
    const ATTACH_LIST_LIMIT = 20;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiscountFiveShopSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new DiscountFiveShop();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionAttachList()
    {
        $discounts = DiscountFiveShop::find()
            ->noCategory()
            ->limit(self::ATTACH_LIST_LIMIT)
            ->all();

        $categories = Category::find()->all();

        return $this->render('attach-list', [
            'discounts' => $discounts,
            'categories' => $categories,
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
     * @return bool
     * @throws \Exception
     */
    public function actionAttachToCategory(int $id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());

        $model->save();

        return true;
    }

    /**
     * @param int $id
     * @return DiscountFiveShop
     * @throws \Exception
     */
    protected function findModel(int $id)
    {
        if (($model = DiscountFiveShop::findOne($id)) !== null) {

            return $model;
        }

        throw new \Exception('Запись не найдена');
    }
}