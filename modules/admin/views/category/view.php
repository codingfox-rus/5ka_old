<?php
/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $categoryWordKey app\models\CategoryWordKey */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="category-view">
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin([
                'action' => [
                    '/admin/category/update',
                    'id' => $model->id,
                ]
            ]) ?>

            <div class="row">
                <div class="col-md-11">
                    <?= $form->field($model, 'name')->label(false) ?>
                </div>

                <div class="col-md-1">
                    <?= Html::submitButton('<i class="fa fa-floppy-o"></i>', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end() ?>

            <h4>Скидки в Пятерочке</h4>

            <?php if ($model->fiveShopDiscounts): ?>

                <table class="table">
                    <?php foreach ($model->fiveShopDiscounts as $fiveShopDiscount): ?>

                        <tr>
                            <td>
                                <?= Html::img($fiveShopDiscount->preview, [
                                    'class' => 'img-responsive',
                                ]) ?>
                            </td>
                            <td>
                                <?= h($fiveShopDiscount->name) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </table>

            <?php endif; ?>
        </div>

        <div class="col-md-6">

            <h4>Ключевые слова:</h4>

            <?php if ($model->wordKeys): ?>

                <table class="table-striped table-condensed">
                    <?php foreach ($model->wordKeys as $wordKey): ?>

                        <tr>
                            <td>
                                <?= h($wordKey->key) ?>
                            </td>

                            <td>
                                <?= Html::a('<i class="fa fa-pencil-square-o"></i>', [
                                    '/admin/category/load-update-key-form',
                                    'id' => $wordKey->id,
                                ], [
                                    'class' => 'btn btn-primary btn-xs load-form-update-key',
                                ]) ?>
                                &nbsp;

                                <?= Html::a('<i class="fa fa-close"></i>', [
                                    '/admin/category/delete-key',
                                    'id' => $wordKey->id,
                                ], [
                                    'class' => 'btn btn-danger btn-xs delete-key',
                                ]) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </table>

                <?php $form = ActiveForm::begin([
                    'action' => ['/admin/category/add-key'],
                    'options' => [
                        'class' => 'form-add-key'
                    ]
                ]) ?>

                <div class="row">
                    <div class="col-md-11">
                        <?= $form->field($categoryWordKey, 'key') ?>

                        <?= $form->field($categoryWordKey, 'categoryId')->hiddenInput()->label(false) ?>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end() ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Управление ключами категории -->
<div class="modal fade" id="modalManageWordKey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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