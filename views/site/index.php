<?php
/* @var $this yii\web\View */
/* @var $discounts app\models\Discount[] */

use yii\helpers\Html;

$this->title = 'Мониторинг скидок';
?>

<div class="site-index">

    <?php foreach ($discounts as $discount) { ?>

        <?= $discount->productName ?>

        <?= Html::img($discount->preview) ?>

    <?php } ?>
</div>
