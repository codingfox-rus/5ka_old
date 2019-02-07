<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Market */

$this->title = 'Обновить магазин: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="market-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
