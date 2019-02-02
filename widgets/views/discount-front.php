<?php
/* @var $discount app\models\Discount */

use yii\helpers\Html;
?>

<div class="discount-front-widget" data-id="<?= $discount->id ?>">

    <div class="row">
        <div class="col-sm-6">

            <a
                href="<?= $discount->bigPreview ?>"
                target="_blank"
                title="<?= $discount->productName ?>"
            >
                <img
                    src="<?= $discount->bigPreview ?>"
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
                <div>
                    Обычная цена: <i class="fa fa-rub"></i> <?= $discount->regularPrice ?>
                </div>

                <div class="price-stripe regular-price-stripe label-danger"></div>

                <div>
                    Цена со скидкой: <i class="fa fa-rub"></i> <?= $discount->specialPrice ?>
                </div>

                <div class="price-stripe discount-price-stripe label-success" style=""></div>
            </div>
        </div>
    </div>
</div>
