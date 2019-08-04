<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\TagKeySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Ключи тегов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-key-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить ключ тега', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'tagId',
            'key',
            'updatedAt',

            ['class' => \yii\grid\ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
