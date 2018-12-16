<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Category;
use app\models\CategoryWordKey;

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
                'attribute' => 'wordKeys',
                'format' => 'html',
                'value' => function (Category $model) {

                    return implode('', array_map(function(CategoryWordKey $item){

                        return Html::a($item->key, '#', [
                            'class' => 'btn btn-success btn-xs load-form-update-key',
                            'data-id' => $item->id,
                        ]);

                    }, json_decode($model->wordKeys)));
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="text-center">{update} {delete} {load-add-key-form}</div>',
                'buttons' => [
                    'update' => function ($url) {

                        return Html::a('<i class="fa fa-pencil-square-o"></i>', $url, [
                            'class' => 'btn btn-primary btn-xs',
                        ]);
                    },
                    'delete' => function ($url) {

                        return Html::a('<i class="fa fa-close"></i>', $url, [
                            'class' => 'btn btn-danger btn-xs',
                            'data' => [
                                'confirm' => 'Вы уверены?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                    'load-add-key-form' => function ($url) {

                        return Html::a('<i class="fa fa-plus"></i>', $url, [
                            'class' => 'btn btn-success btn-xs load-add-key-form'
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<!-- Управление ключами категории -->
<div class="modal fade" id="modalManageWordKey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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