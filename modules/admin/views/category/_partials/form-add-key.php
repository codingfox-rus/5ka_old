<?php
/* @var $category app\models\Category */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => [
        '/admin/category/add-key',
        'id' => $category->id,
    ],
    'options' => [
        'class' => 'form-add-key'
    ]
]) ?>

<?= $form->field($category, 'key') ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
</div>

<?php ActiveForm::end() ?>
