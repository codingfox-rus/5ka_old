<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pages yii\data\Pagination */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\widgets\DiscountFront;
use yii\widgets\LinkPager;

$this->title = 'Мониторинг скидок';
?>

<div class="site-index">

    <div class="row">
        <div class="col-md-3">
            <div class="left-menu">
                <?= Html::beginForm('', 'get', [
                    'class' => 'left-menu-search'
                ]) ?>

                <div class="form-group">
                    <?= Html::textInput('productName', ArrayHelper::getValue($_GET, 'productName'), [
                        'class' => 'form-control',
                        'placeholder' => 'Наименование продукта',
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary btn-block']) ?>
                </div>

                <?= Html::endForm() ?>
            </div>
        </div>

        <div class="col-md-9">
            <div class="discount-wrapper">

                <?php foreach ($dataProvider->getModels() as $discount) { ?>

                    <?= DiscountFront::widget([
                        'discount' => $discount,
                    ]) ?>

                <?php } ?>

                <?= LinkPager::widget([
                    'pagination' => $pages,
                ]) ?>
            </div>
        </div>
    </div>
</div>
