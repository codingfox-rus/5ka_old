<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\RegionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use app\models\Region;

$this->title = 'Регионы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'name',

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
                'template' => '<div class="text-center">{view}</div>',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('<i class="fa fa-list"></i>', $url, [
                            'class' => 'btn btn-primary btn-sm',
                            'title' => 'Статистика по локациям',
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
