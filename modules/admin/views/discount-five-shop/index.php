<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountFiveShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\DiscountFiveShop;
use app\components\parsers\FiveShop;

$this->title = 'Скидки в пятерочке';
$this->params['breadcrumbs'][] = ['label' => 'Админка', 'url' => ['/admin/main/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-index">

    <?= Html::a('Прикрепить категории', ['/admin/discount-five-shop/attach-list'], [
        'class' => 'btn btn-primary'
    ]) ?>
    <br><br>

    <?php Pjax::begin(['id' => 'discounts']) ?>

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

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'template' => '<div class="text-center">{load-attach-to-category-form}</div>',
                'buttons' => [
                    'load-attach-to-category-form' => function ($url) {

                        return Html::a('<i class="fa fa-paperclip"></i>', $url, [
                            'class' => 'btn btn-primary btn-xs attach-discount-to-category'
                        ]);
                    }
                ],
            ],
        ],
    ]) ?>

    <?php Pjax::end() ?>
</div>

<!-- Прикрепляем скидку к категории -->
<div class="modal fade" id="modalAttachDiscountToCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Привязать скидку к категории</h4>
            </div>
            <div class="modal-body">
                <!-- Ajax loading -->
            </div>
        </div>
    </div>
</div>