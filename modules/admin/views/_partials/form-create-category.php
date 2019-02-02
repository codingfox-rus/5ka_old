<?php
/* @var $model app\models\Category */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => ['/admin/category/create-from-discount-view'],
    'options' => [
        'class' => 'form-create-category-for-discount',
    ]
]) ?>

<?= $form->field($model, 'name') ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
</div>

<?php ActiveForm::end(); ?>
