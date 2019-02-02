<?php
namespace app\widgets;

use yii\base\Widget;

class DiscountData extends Widget
{
    /** @var \app\models\Discount */
    public $model;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('discount-data', [
            'model' => $this->model,
        ]);

    }
}