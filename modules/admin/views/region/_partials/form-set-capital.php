<?php
/* @var $model app\models\Region */
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$locations = ArrayHelper::map($model->getLocations()->all(), 'id', 'name');
?>

<?php $form = ActiveForm::begin([
    'action' => [
        '/admin/region/set-capital',
        'id' => $model->id
    ]
]) ?>

<?= $form->field($model, 'capitalLocationId')->dropDownList(
    $locations,
    [
        'prompt' => 'Выберите локацию',
        'required' => true,
    ]
) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
</div>

<?php ActiveForm::end() ?>
