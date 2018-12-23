<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Category;
use app\models\CategoryWordKey;

class DiscountCategory extends Widget
{
    /** @var \app\models\Discount */
    public $model;

    /** @var array */
    public $categories;

    /** @var \app\models\CategoryWordKey */
    public $wordKey;

    public function init()
    {
        $this->categories = Category::find()->all();

        $this->wordKey = new CategoryWordKey();
    }

    public function run()
    {
        return $this->render('discount-category', [
            'model' => $this->model,
            'categories' => $this->categories,
            'wordKey' => $this->wordKey,
        ]);
    }
}