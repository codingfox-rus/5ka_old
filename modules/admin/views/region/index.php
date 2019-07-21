<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\RegionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totalEnabledLocations int */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use app\models\Region;

$this->title = 'Регионы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-index">
    <div class="clearfix">
        <div class="pull-left">
            <h2>
                <?= Html::encode($this->title) ?>
            </h2>
        </div>
        <div class="pull-right">
            <?= Html::a('Включить только столицы регионов', ['/admin/region/enable-only-capitals'], [
                'class' => 'btn btn-success btn-sm'
            ]) ?>
            &nbsp;

            <?= Html::a('Отключить все локации ('. $totalEnabledLocations .')', ['/admin/region/disable-total-locations'], [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Вы уверены?',
                ],
            ]) ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'name',

            [
                'attribute' => 'capitalLocationId',
                'value' => function (Region $model) {
                    if ($model->capitalLocation) {
                        return $model->capitalLocation->name;
                    }

                    return '-';
                }
            ],

            [
                'attribute' => 'updatedAt',
                'value' => function (Region $model) {
                    if ($model->updatedAt) {
                        return date('H:i:s d.m.Y', $model->updatedAt);
                    }

                    return '-';
                }
            ],

            [
                'class' => ActionColumn::class,
                'template' => '<div class="text-center">{view} {view-set-capital-form}</div>',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('<i class="fa fa-list"></i>', $url, [
                            'class' => 'btn btn-primary btn-sm',
                            'title' => 'Статистика по локациям',
                        ]);
                    },
                    'view-set-capital-form' => function ($url) {
                        return Html::a('<i class="fa fa-building-o"></i>', $url, [
                            'class' => 'btn btn-success btn-sm view-set-capital-form',
                            'title' => 'Установить столицу региона',
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<!-- Управление параметрами региона -->
<div class="modal fade" id="modalManageRegion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <!-- Ajax loading -->
                </h4>
            </div>
            <div class="modal-body">
                <!-- Ajax loading -->
            </div>
        </div>
    </div>
</div>