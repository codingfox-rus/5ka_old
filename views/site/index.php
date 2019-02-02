<?php
/* @var $this yii\web\View */
/* @var $discounts app\models\Discount[] */

use yii\helpers\Html;
use app\widgets\DiscountFront;

$this->title = 'Мониторинг скидок';
?>

<div class="site-index">

    <div class="discount-wrapper">

        <?php foreach ($discounts as $discount) { ?>

            <?= DiscountFront::widget([
                'discount' => $discount,
            ]) ?>

        <?php } ?>

    </div>
</div>
