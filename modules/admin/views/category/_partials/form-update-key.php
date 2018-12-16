<?php
/* @var $key app\models\CategoryWordKey */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => [
        '/category-update-key',
        'id' => $key->id,
    ],
    'options' => [
        'class' => 'form-update-key',
    ],
]) ?>

<?= $form->field($key, 'key')->textInput() ?>

<div class="form-group clearfix">
    <div class="pull-left">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']); ?>
    </div>

    <div class="pull-right">
        <?= Html::a('Удалить', [
            '/category/delete-key',
            'id' => $key->id,
        ], [
            'class' => 'btn btn-danger delete-key',
        ]) ?>
    </div>
</div>

<?php ActiveForm::end() ?>
