<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\DiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Discount;
use app\models\Location;

$this->title = 'Скидки';

$locationId = Yii::$app->request->get('locationId');

if ($locationId) {

    $location = Location::findOne($locationId);

    $this->params['breadcrumbs'][] = ['label' => $location->region->name, 'url' => ['/admin/region/view', 'id' => $location->regionId]];
}

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
                'attribute' => 'locationId',
                'value' => static function (Discount $model) {
                    if ($model->location) {
                        return $model->location->name;
                    }

                    return '-';
                }
            ],
            [
                'label' => 'Превью',
                'format' => 'html',
                'value' => static function (Discount $model) {

                    return Html::img($model->smallPreview, [
                        'class' => 'img-responsive',
                        'width' => '200'
                    ]);
                }
            ],
            'productName',
            [
                'label' => 'Цены',
                'format' => 'html',
                'content' => static function (Discount $model) {

                    $out[] = '<i class="fa fa-rub"></i>&nbsp;&nbsp;'. $model->regularPrice;
                    $out[] = '<i class="fa fa-rub"></i>&nbsp;&nbsp;'. $model->specialPrice;
                    $out[] = '<i class="fa fa-percent"></i>&nbsp;&nbsp;'. $model->discountPercent;

                    return implode('<br>', $out);
                }
            ],
            [
                'label' => 'Время',
                'format' => 'html',
                'content' => static function (Discount $model) {

                    $out[] = '<i class="fa fa-play"></i>&nbsp;&nbsp;'. date('H:i d.m.Y', $model->dateStart);
                    $out[] = '<i class="fa fa-stop"></i>&nbsp;&nbsp;'. date('H:i d.m.Y', $model->dateEnd);

                    return implode('<br>', $out);
                }
            ],
            [
                'class' => ActionColumn::class,
                'header' => 'Действия',
                'template' => '<div class="text-center">{view}</div>',
                'buttons' => [
                    'view' => static function ($url) {

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