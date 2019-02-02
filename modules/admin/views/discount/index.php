<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Discount;
use app\models\Category;

$this->title = 'Скидки';
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
                'attribute' => 'market',
                'filter' => Discount::getMarkets(),
                'value' => function (Discount $model) {

                    return Discount::getMarkets()[$model->market];
                }
            ],

            [
                'attribute' => 'categoryId',
                'filter' => ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                'value' => function (Discount $model) {

                    return $model->category ? $model->category->name : '-';
                }
            ],

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
                'label' => 'Цены',
                'format' => 'html',
                'content' => function (Discount $model) {

                    $out[] = '<i class="fa fa-rub"></i>&nbsp;&nbsp;'. $model->regularPrice;
                    $out[] = '<i class="fa fa-rub"></i>&nbsp;&nbsp;'. $model->specialPrice;
                    $out[] = '<i class="fa fa-percent"></i>&nbsp;&nbsp;'. $model->discountPercent;

                    return implode('<br>', $out);
                }
            ],

            [
                'label' => 'Время',
                'format' => 'html',
                'content' => function (Discount $model) {

                    $out[] = '<i class="fa fa-play"></i>&nbsp;&nbsp;'. date('H:i d.m.Y', $model->dateStart);
                    $out[] = '<i class="fa fa-stop"></i>&nbsp;&nbsp;'. date('H:i d.m.Y', $model->dateEnd);

                    return implode('<br>', $out);
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