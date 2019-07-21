<?php
/* @var $model app\models\Settings */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Settings;

$this->title = 'Настройки проекта';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="settings-update">
    <div class="row">
        <div class="col-md-6">
            <h4>
                <?= $this->title ?>
            </h4>
            <br>

            <?php $form = ActiveForm::begin() ?>

            <?= $form->field($model, 'apiVersion')->dropDownList(
                Settings::getApiVersions()
            ) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']) ?>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
