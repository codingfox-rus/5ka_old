<?php
/* @var $prev app\models\Discount */
/* @var $model app\models\Discount */
/* @var $next app\models\Discount */

use yii\helpers\Html;
use app\widgets\DiscountData;

$this->title = $model->productName .' - Обновить параметры';
$this->params['breadcrumbs'][] = ['label' => 'Все скидки', 'url' => ['/admin/discount/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-view">

    <div class="row">
        <div class="col-md-1">
            <?php if ($prev): ?>

                <?= Html::a('<i class="fa fa-chevron-left fa-3x"></i>', [
                    '/admin/discount/view',
                    'id' => $prev->id,
                ], [
                    'class' => 'text-primary discount-view-control'
                ]) ?>

            <?php endif; ?>
        </div>

        <div class="col-md-10">
            <?= DiscountData::widget([
                'model' => $model,
            ]) ?>

            <div class="text-center">
                <?php if ($model->stat) { ?>

                    <?= Html::a('Отключить сбор статистики', [
                        '/admin/discount/disable-stat',
                        'id' => $model->id,
                    ], [
                        'class' => 'btn btn-danger'
                    ]) ?>

                <?php } else { ?>

                    <?= Html::a('Включить сбор статистики', [
                        '/admin/discount/enable-stat',
                        'id' => $model->id,
                    ], [
                        'class' => 'btn btn-success',
                    ]) ?>

                <?php } ?>
            </div>
        </div>

        <div class="col-md-1">
            <?php if ($next): ?>

                <?= Html::a('<i class="fa fa-chevron-right fa-3x"></i>', [
                    '/admin/discount/view',
                    'id' => $next->id,
                ], [
                    'class' => 'text-primary discount-view-control'
                ]) ?>

            <?php endif; ?>
        </div>
    </div>
</div>