<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Category;

class DiscountCategory extends Widget
{
    /** @var \app\models\Discount */
    public $model;

    /** @var array */
    public $categories;

    public function init()
    {
        $this->categories = Category::find()->all();
    }

    public function run()
    {
        return $this->render('discount-category', [
            'model' => $this->model,
            'categories' => $this->categories,
        ]);
    }
}