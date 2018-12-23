<?php
namespace app\widgets;

use yii\base\Widget;

class DiscountData extends Widget
{
    /** @var \app\models\interfaces\iMarket */
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