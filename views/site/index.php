<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pages yii\data\Pagination */

use yii\widgets\LinkPager;
use app\widgets\LeftMenu;
use app\widgets\DiscountFront;

if (empty($this->title)) {
    $this->title = 'Мониторинг скидок';
}
?>

<div class="site-index">
    <div class="row">
        <div class="col-md-3">
            <div class="left-menu">
                <?= LeftMenu::widget() ?>
            </div>
        </div>

        <div class="col-md-9">
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
    </div>
</div>
