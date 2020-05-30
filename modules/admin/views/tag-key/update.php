<?php
/* @var $this yii\web\View */
/* @var $model app\models\TagKey */
use yii\helpers\Html;

$this->title = 'Обновить ключ тега: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ключи тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="tag-key-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
