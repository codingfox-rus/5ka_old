<?php
namespace app\widgets;

use yii\base\Widget;

class DiscountData extends Widget
{
    /** @var \app\models\interfaces\iDiscount */
    public $model;

    /** @var string */
    public $siteUrl;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('discount-data', [
            'model' => $this->model,
            'siteUrl' => $this->siteUrl,
        ]);

    }
}