<?php
/* @var $discounts app\models\DiscountFiveShop[] */
/* @var $discount app\models\DiscountFiveShop */
/* @var $categories app\models\Category[] */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Быстрое прикрепление категорий';
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['/admin/discount-five-shop/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-attach-list">

    <?php Pjax::begin(['id' => 'discountAttachList']) ?>

    <table class="table">
        <?php foreach ($discounts as $discount): ?>

            <tr>
                <td>
                    <?= Html::img($discount->preview, [
                        'class' => 'img-responsive',
                    ]) ?>
                </td>
                <td>
                    <?= Html::encode($discount->name) ?>
                </td>
                <td>
                    <?php $form = ActiveForm::begin([
                        'action' => [
                            '/admin/discount-five-shop',
                            'id' => $discount->id,
                        ],
                        'options' => [
                            'class' => 'form-inline form-quick-attach'
                        ]
                    ]) ?>

                    <?= $form->field($discount, 'categoryId')->dropDownList(
                        ArrayHelper::map($categories, 'id', 'name'),
                        [
                            'prompt' => '',
                            'placeholder' => 'Выберите категорию',
                        ]
                    )->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-check"></i>', ['class' => 'btn btn-success']); ?>
                    </div>

                    <?php ActiveForm::end() ?>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>

    <?php Pjax::end() ?>
</div>
