<?php
/* @var $model app\models\Discount */
/* @var $categories app\models\Category[] */
/* @var $category app\models\Category */

use yii\helpers\Html;
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

<?= Html::beginForm(
    [
        '/admin/discount/attach-to-category',
        'id' => $model->id
    ],
    'post',
    [
        'class' => 'form-attach-discount-to-category'
    ]
) ?>

<?php foreach ($categories as $category) { ?>

    <div class="radio">
        <label>
            <input
                type="radio"
                name="categoryId"
                class="category-radio"
                value="<?= $category->id ?>"
                <?= $model->categoryId === $category->id ? 'checked' : '' ?>
            >

            <?= $category->name ?>
        </label>
    </div>

<?php } ?>

<?php Html::endForm() ?>

<?php Pjax::end() ?>

<!-- Добавляем новую категорию -->
<div class="modal fade" id="modalDiscountCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
