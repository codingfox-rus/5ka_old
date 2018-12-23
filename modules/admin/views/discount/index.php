<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Discount;
use app\components\markets\FiveShop;

$this->title = 'Скидки в пятерочке';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-index">

    <?php Pjax::begin(['id' => 'discounts']) ?>

    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [

            [
                'attribute' => 'imageSmall',
                'format' => 'html',
                'value' => function (Discount $model) {

                    return Html::img($model->preview, [
                        'class' => 'img-responsive',
                    ]);
                }
            ],

            'productName',

            [
                'attribute' => 'regularPrice',
                'format' => 'html',
                'value' => function (Discount $model) {

                    return '<span class="glyphicon glyphicon-ruble"></span>'. $model->regularPrice;
                }
            ],

            [
                'attribute' => 'specialPrice',
                'format' => 'html',
                'value' => function (Discount $model) {

                    return '<span class="glyphicon glyphicon-ruble"></span>'. $model->specialPrice;
                }
            ],

            [
                'attribute' => 'discountPercent',
                'value' => function (Discount $model) {

                    return $model->discountPercent .'%';
                }
            ],

            [
                'attribute' => 'dateStart',
                'value' => function (Discount $model) {

                    return date('d.m.Y H:i', $model->dateStart);
                }
            ],

            [
                'attribute' => 'dateEnd',
                'value' => function (Discount $model) {

                    return date('d.m.Y H:i', $model->dateEnd);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'template' => '<div class="text-center">{view}</div>',
                'buttons' => [
                    'view' => function ($url) {

                        return Html::a('<i class="fa fa-eye"></i>', $url, [
                            'class' => 'btn btn-primary'
                        ]);
                    }
                ],
            ],
        ],
    ]) ?>

    <?php Pjax::end() ?>
</div>