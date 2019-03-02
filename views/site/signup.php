<?php
/* @var $model app\models\SignupForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация на сайте';
?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'email')->textInput([
            'type' => 'email',
            'placeholder' => 'Введите email',
        ])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Регистрация', [
                'class' => 'btn btn-primary btn-block'
            ]) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>
