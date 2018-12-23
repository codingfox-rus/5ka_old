<?php
/* @var $prev app\models\DiscountFiveShop */
/* @var $model app\models\DiscountFiveShop */
/* @var $next app\models\DiscountFiveShop */

use yii\helpers\Html;
use app\components\parsers\FiveShop;
use app\widgets\DiscountData;
use app\widgets\DiscountCategory;

$this->title = $model->name .' - Обновить параметры';
$this->params['breadcrumbs'][] = ['label' => 'Скидки в пятерочке', 'url' => ['/admin/discount-five-shop/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-view">

    <div class="row">
        <div class="col-md-1">
            <?php if ($prev): ?>

                <?= Html::a('<i class="fa fa-chevron-left fa-3x"></i>', [
                    '/admin/discount-five-shop/view',
                    'id' => $prev->id,
                ], [
                    'class' => 'text-primary discount-view-control'
                ]) ?>

            <?php endif; ?>
        </div>

        <div class="col-md-5">
            <?= DiscountData::widget([
                'model' => $model,
                'siteUrl' => FiveShop::SITE_URL,
            ]) ?>
        </div>

        <div class="col-md-5">
            <?= DiscountCategory::widget([
                'model' => $model,
            ]) ?>
        </div>

        <div class="col-md-1">
            <?php if ($next): ?>

                <?= Html::a('<i class="fa fa-chevron-right fa-3x"></i>', [
                    '/admin/discount-five-shop/view',
                    'id' => $next->id,
                ], [
                    'class' => 'text-primary discount-view-control'
                ]) ?>

            <?php endif; ?>
        </div>
    </div>
</div>