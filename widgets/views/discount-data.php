<?php
/* @var $model app\models\interfaces\iDiscount */
/* @var $siteUrl string */

use yii\widgets\DetailView;
use yii\helpers\Html;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'preview',
            'format' => 'html',
            'value' => Html::img($model->getPreview(), ['class' => 'img-responsive'])
        ],
        'name',
        'description',
        'regularPrice',
        'specialPrice',
        'discountPercent',
    ],
]) ?>