<?php
/* @var $locations array */
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
?>

<!-- Modal -->
<div class="modal fade" id="modalSelectCity" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Выберите город</h4>
            </div>
            <div class="modal-body">
                <?= Html::beginForm('/site/select-city') ?>

                    <div class="form-group">
                        <?= Select2::widget([
                            'name' => 'locationId',
                            'data' => $locations,
                            'options' => [
                                'placeholder' => 'Выберите населенный пункт',
                                'allowClear' => true,
                                'dropdownParent' => new JsExpression('$("#modalSelectCity")'),
                                'required' => true,
                            ],
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Выбрать', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>

                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>
