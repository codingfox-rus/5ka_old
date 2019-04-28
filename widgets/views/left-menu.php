<?php
/** @var $markets array */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\DiscountSearch;

$sortingOrder = ArrayHelper::getValue($_GET, 'sortingOrder');
?>

<div class="left-menu-widget">
    <?= Html::beginForm('', 'get', [
        'class' => 'left-menu-search'
    ]) ?>
    <!--
    <div class="form-group">
        <?php foreach ($markets as $code => $name) { ?>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="markets[]" value="<?= $code ?>"> <?= $name ?>
                </label>
            </div>

        <?php } ?>
    </div>-->

    <div class="form-group">
        <?= Html::textInput('productName', ArrayHelper::getValue($_GET, 'productName'), [
            'class' => 'form-control',
            'placeholder' => 'Наименование продукта',
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::dropDownList(
            'sortingOrder',
            $sortingOrder,
            DiscountSearch::getSortingOptions(),
            [
                'class' => 'form-control',
                'prompt' => 'Порядок вывода',
            ]
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary btn-block']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
