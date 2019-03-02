<?php
/* @var $model app\models\Feedback */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

if (empty($this->title)) {
    $this->title = 'Обратная связь';
}
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">

        <?php if (Yii::$app->session->hasFlash('feedbackSubmitted')) { ?>

            <div class="text-center">
                Спасибо за обращение, мы ответим Вам в ближайшее время!
            </div>

        <?php } else { ?>

            <h4><?= $this->title ?></h4>

            <?php $form = ActiveForm::begin() ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

            <?= $form->field($model, 'subject')->textInput() ?>

            <?= $form->field($model, 'message')->textarea([
                'rows' => 4,
            ]) ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-3">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block']) ?>
            </div>

            <?php ActiveForm::end() ?>

        <?php } ?>
    </div>
</div>
