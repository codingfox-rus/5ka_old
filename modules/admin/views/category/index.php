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
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="text-center">{view}</div>',
                'buttons' => [
                    'view' => function ($url) {

                        return Html::a('<i class="fa fa-cog"></i>', $url, [
                            'class' => 'btn btn-primary',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php
/*
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
]
*/


?>

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