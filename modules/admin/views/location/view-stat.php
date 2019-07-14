<?php
/* @var $model app\models\Location */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Stat;

$this->title = $model->name;

if ($model->region) {
    $this->params['breadcrumbs'][] = ['label' => 'Регион: '. $model->region->name, 'url' => ['/admin/region/view/', 'id' => $model->regionId]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="view-stat-index">
    <h4><?= $this->title ?></h4>
    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Превью',
                'format' => 'html',
                'content' => function (Stat $model) {
                    if ($model->product && $model->product->preview) {

                        return Html::img($model->product->preview, [
                            'width' => '100',
                        ]);
                    }

                    return '-';
                }
            ],

            [
                'label' => 'Наименование',
                'format' => 'html',
                'content' => function (Stat $model) {
                    if ($model->product) {

                        $totalProductStat = \count($model->data);

                        return Html::a($model->product->name .' ('. $totalProductStat .')', [
                            '/admin/location/view-product-stat',
                            'locationId' => $model->locationId,
                            'productId' => $model->productId,
                        ], [
                            'title' => 'Статистика по товару',
                        ]);
                    }

                    return "Продукта с ID {$model->productId} нет в базе";
                }
            ],

            [
                'attribute' => 'nextTurnAt',
                'value' => function (Stat $model) {
                    if ($model->nextTurnAt) {
                        return date('H:i d.m.Y', $model->nextTurnAt);
                    }

                    return '-';
                }
            ],

            [
                'attribute' => 'createdAt',
                'value' => function (Stat $model) {
                    return date('H:i d.m.Y', $model->createdAt);
                }
            ],

            [
                'attribute' => 'updatedAt',
                'value' => function (Stat $model) {
                    return date('H:i d.m.Y', $model->updatedAt);
                }
            ],
        ],
    ]) ?>
</div>
