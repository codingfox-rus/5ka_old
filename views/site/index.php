<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pages yii\data\Pagination */

use yii\helpers\Html;
use app\widgets\DiscountFront;
use yii\widgets\LinkPager;

$this->title = 'Мониторинг скидок';
?>

<div class="site-index">

    <div class="discount-wrapper">

        <?php foreach ($dataProvider->getModels() as $discount) { ?>

            <?= DiscountFront::widget([
                'discount' => $discount,
            ]) ?>

        <?php } ?>

        <?= LinkPager::widget([
            'pagination' => $pages,
        ]) ?>
    </div>
</div>
