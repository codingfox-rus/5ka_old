<?php
/* @var $discount app\models\Discount */

use yii\helpers\Html;
?>

<div class="discount-front-widget" data-id="<?= $discount->id ?>">

    <div class="row">
        <div class="col-sm-6">
            <a
                href="<?= $discount->smallPreview ?? '' ?>"
                target="_blank"
                title="<?= $discount->productName ?>"
            >
                <img
                    src="<?= $discount->smallPreview ?? '' ?>"
                    alt="<?= $discount->productName ?>"
                    class="img-responsive"
                >
            </a>
        </div>

        <div class="col-sm-6">

            <div class="discount-prices"
                 data-rprice="<?= $discount->regularPrice ?>"
                 data-sprice="<?= $discount->specialPrice ?>"
            >
                <div class="discount-product-name">
                    <?= Html::encode($discount->productName) ?>
                </div>

                <div title="Обычная цена">
                    <i class="fa fa-rub"></i> <?= $discount->regularPrice ?>
                </div>

                <div title="Обычная цена" class="price-stripe regular-price-stripe label-danger"></div>

                <div title="Цена со скидкой">
                    <i class="fa fa-rub"></i> <?= $discount->specialPrice ?>
                </div>

                <div title="Цена со скидкой" class="price-stripe discount-price-stripe label-success" style=""></div>
            </div>

            <div class="discount-period" title="Московское время">
                <?= Yii::$app->formatter->asDate($discount->dateStart) ?> - <?= Yii::$app->formatter->asDate($discount->dateEnd) ?>
            </div>
        </div>
    </div>
</div>
