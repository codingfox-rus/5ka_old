<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Market */

$this->title = 'Добавить магазин';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
