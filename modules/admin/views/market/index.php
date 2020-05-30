<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\MarketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Market;

$this->title = 'Магазины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-index">

    <?php Pjax::begin(); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="clearfix">
        <div class="pull-left">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="pull-right">
            <?= Html::a('Добавить магазин', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            [
                'attribute' => 'logo',
                'format' => 'html',
                'value' => static function (Market $model) {

                    return Html::img(Yii::getAlias('@web') .'/'. $model->logo, [
                        'class' => 'img-responsive',
                        'style' => 'max-width: 100px',
                    ]);
                }
            ],

            'name',

            'code',

            [
                'class' => ActionColumn::class,
                'header' => 'Действия',
                'template' => '<div class="text-center">{view} {update}</div>',
                'buttons' => [
                    'view' => static function ($url) {

                        return Html::a('<i class="fa fa-list"></i>', $url, [
                            'class' => 'btn btn-primary btn-xs',
                        ]);
                    },
                    'update' => static function ($url) {

                        return Html::a('<i class="fa fa-pencil-square-o"></i>', $url, [
                            'class' => 'btn btn-success btn-xs',
                        ]);
                    }
                ],
            ],
        ],
    ]) ?>
    <?php Pjax::end(); ?>
</div>
