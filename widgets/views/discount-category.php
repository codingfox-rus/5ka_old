<?php
/* @var $model app\models\Discount */
/* @var $categories app\models\Category[] */
/* @var $category app\models\Category */
/* @var $wordKey app\models\CategoryWordKey */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>

<?php Pjax::begin(['id' => 'discountCategory']) ?>

<!-- Добавляем категорию, если нет необходимой -->
<?= Html::a('Добавить категорию', [
    '/admin/discount/load-create-category-form',
    'id' => $model->id,
], [
    'class' => 'btn btn-success btn-block load-create-category-form',
]) ?>
<br>

<!-- Прикрепляем скидку к определенной категории -->
<?php $form = ActiveForm::begin([
    'action' => [
        '/admin/discount/attach-to-category',
        'id' => $model->id
    ],
    'options' => [
        'class' => 'form-attach-discount-to-category'
    ]
]) ?>

    <?= $form->field($model, 'categoryId')->dropDownList(
        ArrayHelper::map($categories, 'id', 'name'),
        [
            'prompt' => '',
            'placeholder' => 'Выберите категорию',
        ]
    )->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Прикрепить к категории', [
            'class' => 'btn btn-primary btn-block'
        ]); ?>
    </div>

<?php ActiveForm::end() ?>

<hr>

<?php if ($model->category): ?>

    <!-- Добавление недостающего ключа -->
    <?php $form = ActiveForm::begin([
        'action' => ['/admin/category/add-key'],
        'options' => [
            'class' => 'form-add-key-for-discount form-inline'
        ]
    ]) ?>

    <?= $form->field($wordKey, 'key')->textInput([
        'placeholder' => 'Введите ключ',
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-plus"></i>', [
            'class' => 'btn btn-success'
        ]) ?>
    </div>

    <?php ActiveForm::end() ?>

    <!-- Список ключей -->
    <ul class="list-group">
        <?php foreach ($category->wordKeys as $key): ?>

            <li class="list-group-item">
                <?= h($key->key) ?>
            </li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<?php Pjax::end() ?>

<!-- Прикрепляем скидку к категории -->
<div class="modal fade" id="modalDiscountCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <!-- Ajax loading -->
                </h4>
            </div>
            <div class="modal-body">
                <!-- Ajax loading -->
            </div>
        </div>
    </div>
</div>