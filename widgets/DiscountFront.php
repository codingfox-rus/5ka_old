<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Discount;

class DiscountFront extends Widget
{
    /** @var Discount */
    public $discount;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('discount-front', [
            'discount' => $this->discount,
        ]);
    }
}