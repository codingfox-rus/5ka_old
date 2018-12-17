<?php
/* @var $model app\models\DiscountFiveShop */
/* @var $categories app\models\Category[] */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => [
        '/admin/controllers/attach-to-category',
        'id' => $model->id,
    ],
    'options' => [
        'class' => 'form-attach-to-category',
    ],
]) ?>

<?= $form->field($model, 'categoryId')->dropDownList(
    ArrayHelper::map($categories, 'id', 'name'),
    [
        'prompt' => 'Выберите категорию',
    ]
) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']) ?>
</div>

<?php ActiveForm::end() ?>