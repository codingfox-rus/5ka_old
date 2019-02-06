<?php
/* @var $this yii\web\View */
/* @var $categories app\models\Category[] */
/* @var $categorizedExists bool */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pages yii\data\Pagination */

use yii\helpers\Html;
use app\widgets\DiscountFront;
use yii\widgets\LinkPager;

$this->title = 'Мониторинг скидок';
?>

<div class="site-index">

    <div class="row">

        <?php if ($categorizedExists) { ?>

            <div class="col-md-3">
                <div class="left-menu">

                    <ul class="list-group">
                        <?php foreach ($categories as $category) { ?>
                            <li class="list-group-item">
                                <?= Html::a($category->name, [
                                    '/',
                                    'categoryId' => $category->id,
                                ], [
                                    'class' => 'btn btn-primary btn-block',
                                ]) ?>
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            </div>

        <?php } ?>

        <div class="<?= $categorizedExists ? 'col-md-9' : 'col-md-12' ?>">
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
