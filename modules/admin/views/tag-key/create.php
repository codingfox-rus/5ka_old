<?php
/* @var $this yii\web\View */
/* @var $model app\models\TagKey */
use yii\helpers\Html;

$this->title = 'Добавить ключ тега';
$this->params['breadcrumbs'][] = ['label' => 'Ключи тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-key-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
