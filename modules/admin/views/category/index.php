<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Category;

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'categories']); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',

            [
                'attribute' => 'isPublic',
                'format' => 'html',
                'value' => function (Category $model) {

                    if ($model->isPublic) {
                        return '<i class="fa fa-plus"></i>';
                    }

                    return '<i class="fa fa-minus"></i>';
                }
            ],

            [
                'class' => \yii\grid\ActionColumn::class,
                'template' => '<div class="text-center">{update}</div>',
                'buttons' => [
                    'view' => function ($url) {

                        return Html::a('<i class="fa fa-pencil-square-o"></i>', $url, [
                            'class' => 'btn btn-primary',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>