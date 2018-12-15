<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountFiveShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\DiscountFiveShop;
use app\components\parsers\FiveShop;

$this->title = 'Все скидки';
?>

<div class="discount-index">

    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [

            [
                'attribute' => 'imageSmall',
                'format' => 'html',
                'value' => function (DiscountFiveShop $model) {

                    return Html::img(FiveShop::SITE_URL . $model->imageSmall, [
                        'class' => 'img-responsive',
                    ]);
                }
            ],

            'name',

            [
                'attribute' => 'regularPrice',
                'format' => 'html',
                'value' => function (DiscountFiveShop $model) {

                    return '<span class="glyphicon glyphicon-ruble"></span>'. $model->regularPrice;
                }
            ],

            [
                'attribute' => 'specialPrice',
                'format' => 'html',
                'value' => function (DiscountFiveShop $model) {

                    return '<span class="glyphicon glyphicon-ruble"></span>'. $model->specialPrice;
                }
            ],

            [
                'attribute' => 'discountPercent',
                'value' => function (DiscountFiveShop $model) {

                    return $model->discountPercent .'%';
                }
            ],

            [
                'attribute' => 'dateStart',
                'value' => function (DiscountFiveShop $model) {

                    return date('d.m.Y H:i', $model->dateStart);
                }
            ],

            [
                'attribute' => 'dateEnd',
                'value' => function (DiscountFiveShop $model) {

                    return date('d.m.Y H:i', $model->dateEnd);
                }
            ],
        ],
    ]) ?>

</div>
